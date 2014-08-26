<?php

namespace PHPOrchestra\ModelBundle\Tests\Model;

/**
 * Description of BaseNodeTest
 */
class EntityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $class
     * @param string $interface
     *
     * @dataProvider providateClassInterfaceRelation
     */
    public function testInstance($class, $interface)
    {
        $fullClass = 'PHPOrchestra\ModelBundle\Document\\' . $class;
        $fullInterface = 'PHPOrchestra\ModelBundle\Model\\' . $interface;
        $entity = new $fullClass();

        $this->assertInstanceOf($fullInterface, $entity);
    }

    /**
     * @return array
     */
    public function providateClassInterfaceRelation()
    {
        return array(
            array('Node', 'NodeInterface'),
            array('Node', 'AreaContainerInterface'),
            array('Node', 'BlockContainerInterface'),
            array('Area', 'AreaInterface'),
            array('Area', 'AreaContainerInterface'),
            array('Block', 'BlockInterface'),
            array('ContentAttribute', 'ContentAttributeInterface'),
            array('Content', 'ContentInterface'),
            array('ContentType', 'ContentTypeInterface'),
            array('FieldIndex', 'FieldIndexInterface'),
            array('ListIndex', 'ListIndexInterface'),
            array('Site', 'SiteInterface'),
            array('Template', 'TemplateInterface'),
            array('Template', 'BlockContainerInterface'),
            array('Template', 'AreaContainerInterface'),
        );
    }
}