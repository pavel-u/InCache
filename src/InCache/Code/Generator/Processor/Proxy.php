<?php
/**
 * This file is part of the InCache library. 
 * 
 * (c) Pavel Yukhnevich <ukhnevich@gmail.com>. 
 * 
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
 */

namespace InCache\Code\Generator\Processor;


class Proxy implements \InCache\Code\Generator\Processor
{
    const TEMPLATE_CLASS = 'class';
    const ACTION_EVICT = 'evict';
    const ACTION_CACHE = 'cache';

    /**
     * @var array
     */
    protected $templates = [
        self::TEMPLATE_CLASS => 'proxy.tpl',
    ];

    /**
     * @var string
     */
    protected $templatesDir;

    /**
     * @var \InCache\Code\Analyzer
     */
    protected $analyzer;

    /**
     * @var array
     */
    protected $methods = [];

    /**
     * @param \InCache\Code\Analyzer\Factory $analyzerFactory
     */
    public function __construct(\InCache\Code\Analyzer\Factory $analyzerFactory)
    {
        $this->templatesDir = __DIR__ . DIRECTORY_SEPARATOR . 'templates';
        $this->analyzer = $analyzerFactory->create();
    }

    /**
     * {@inheritdoc}
     */
    public function setMethods(array $methods)
    {
        $this->methods = $methods;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function process($source)
    {
        if (empty($source)) {
            throw new \InCache\RuntimeException('Unexpected empty code source');
        }
        $classTemplate = $this->getTemplate(self::TEMPLATE_CLASS);
        $this->analyzer->setFile($source);
        $this->analyzer->scan();

        $namespace = $this->analyzer->getNamespace();
        if (isset($namespace)) {
            $namespace = "namespace $namespace;";
            $classTemplate = $this->replace('namespace', $namespace, $classTemplate);
        }

        $classTemplate = $this->replace('className', $this->analyzer->getClassName(), $classTemplate);
        $classTemplate = $this->replace(
            'extends',
            $this->analyzer->getClassName() . \InCache\Code\Generator\Processor\Copy::SUFFIX_VALUE,
            $classTemplate
        );

        $methodsReplacement = '';
        foreach ($this->methods as $method) {
            if (!in_array($method['action'], [self::ACTION_CACHE, self::ACTION_EVICT])) {
                throw new \InCache\UnexpectedValueException('Invalid method action');
            }

            // Use appropriate internal generator method by action
            $methodName = 'generate' . ucfirst($method['action']);
            $methodsReplacement .= $this->$methodName($method);
        }

        return $this->replace('methods', $methodsReplacement, $classTemplate);
    }

    /**
     * Generate cached method by method data
     *
     * @param array $method
     * @return string
     */
    protected function generateCache($method)
    {
        $ttlString = isset($method['ttl']) ? ", {$method['ttl']}" : '';
        if (!empty($method['key'])) {
            $itemKey = "'{$method['key']}'";
            $setKeyString = "\$item->setKey(['stash_default', {$itemKey}]);";
        } else {
            $itemKey = "'{$this->analyzer->getClassName()}/{$method['method']}/' . md5(serialize(func_get_args()))";
            $setKeyString = '';
        }
        return "

    {$this->analyzer->getMethodVisibility($method['method'])} function {$this->analyzer->getMethodSignature($method['method'])}
    {
        \$pool = \$this->poolFactory->get('{$method['type']}');
        \$item = \$pool->getItem({$itemKey});
        if (\$item->isMiss()) {
            \$item->lock();
            \$result = call_user_func_array(array('parent', '{$method['method']}'), func_get_args());
            {$setKeyString}
            \$item->set(\$result{$ttlString});
        }
        return \$item->get();
    }";
    }

    /**
     * Generate evict method by method data
     *
     * @param array $method
     * @return string
     */
    protected function generateEvict($method)
    {
        return "

    {$this->analyzer->getMethodVisibility($method['method'])} function {$this->analyzer->getMethodSignature($method['method'])}
    {
        \$result = call_user_func_array(array('parent', '{$method['method']}'), func_get_args());
        \$pool = \$this->poolFactory->get('{$method['type']}');
        \$item = \$pool->getItem('{$method['key']}');
        \$item->clear();
        return \$result;
    }";

    }

    /**
     * Process single replacement
     *
     * @param string $search
     * @param string $replacement
     * @param string $subject
     * @return mixed
     */
    protected function replace($search, $replacement, $subject)
    {
        return str_replace('{{' . $search . '}}', $replacement, $subject);
    }

    /**
     * Returns template content
     *
     * @param string $type
     * @return string
     */
    protected function getTemplate($type)
    {
        return file_get_contents($this->templatesDir . DIRECTORY_SEPARATOR . $this->templates[$type]);
    }

    /**
     * {@inheritdoc}
     */
    public function getSuffix()
    {
        return '';
    }
}
