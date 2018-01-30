<?php
namespace Nimut\TestingFramework\v90\TestSystem;

/*
 * This file is part of the NIMUT testing-framework project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read
 * LICENSE file that was distributed with this source code.
 */

abstract class AbstractTestSystem extends \Nimut\TestingFramework\TestSystem\AbstractTestSystem
{
    /**
     * Includes the Core Bootstrap class and calls its first few functions
     *
     * @return void
     */
    protected function includeAndStartCoreBootstrap()
    {
        $classLoaderFilepath = $this->getClassLoaderFilepath();

        $classLoader = require $classLoaderFilepath;

        $this->bootstrap->initializeClassLoader($classLoader)
            ->setRequestType(TYPO3_REQUESTTYPE_BE | TYPO3_REQUESTTYPE_CLI)
            ->baseSetup()
            ->loadConfigurationAndInitialize(true);

        $extensionConfigurationService = new ExtensionConfigurationService();
        $extensionConfigurationService->synchronizeExtConfTemplateWithLocalConfigurationOfAllExtensions();
        $this->bootstrap->populateLocalConfiguration();

        $this->bootstrap->loadTypo3LoadedExtAndExtLocalconf(true)
            ->initializeBackendRouter()
            ->setFinalCachingFrameworkCacheConfiguration()
            ->unsetReservedGlobalVariables();
    }
}