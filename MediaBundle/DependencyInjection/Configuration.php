<?php

namespace OpenOrchestra\MediaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('open_orchestra_media');

        $rootNode->children()
            ->scalarNode('tmp_dir')->defaultValue('/tmp')->end()
            ->scalarNode('filesystem')->defaultValue('media_storage')->end()
            ->scalarNode('compression_quality')->defaultValue(75)->end()
            ->arrayNode('document')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('media')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->defaultValue('OpenOrchestra\MediaBundle\Document\Media')->end()
                            ->scalarNode('repository')->defaultValue('OpenOrchestra\MediaBundle\Repository\MediaRepository')->end()
                        ->end()
                    ->end()
                    ->arrayNode('media_folder')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->defaultValue('OpenOrchestra\MediaBundle\Document\MediaFolder')->end()
                            ->scalarNode('repository')->defaultValue('OpenOrchestra\MediaBundle\Repository\FolderRepository')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('thumbnail')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('max_height')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('max_height')->defaultValue(100)->end()
                        ->end()
                    ->end()
                    ->arrayNode('max_width')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('max_width')->defaultValue(100)->end()
                        ->end()
                    ->end()
                    ->arrayNode('rectangle')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('width')->defaultValue(100)->end()
                            ->scalarNode('height')->defaultValue(70)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
