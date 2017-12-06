<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MagentoEse\LumaDESetup\Setup;

use Magento\Framework\Setup;


class InstallData implements Setup\InstallDataInterface
{
    /**
     * @var \Magento\Store\Model\StoreFactory
     */
    private $storeView;

    /**
     * @var \Magento\Store\Api\StoreRepositoryInterfaceFactory
     */
    private $storeRepositoryFactory;

    /**
     * @var \Magento\Store\Model\WebsiteRepository
     */
    private $websiteRepository;

    /**
     * @var \Magento\Store\Model\Website
     */
    private $website;

    /**
     * @var \Magento\Framework\App\State
     */
    private $state;

    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    private $resourceConfig;

    /**
     * InstallData constructor.
     * @param \Magento\Store\Model\StoreFactory $storeView
     * @param \Magento\Store\Api\StoreRepositoryInterfaceFactory $storeRepositoryFactory
     * @param \Magento\Store\Model\WebsiteRepository $websiteRepository
     * @param \Magento\Store\Model\Website $website
     * @param \Magento\Framework\App\State $state
     * @param \Magento\Config\Model\ResourceModel\Config $resourceConfig
     */
    public function __construct(\Magento\Store\Model\StoreFactory $storeView,
                                \Magento\Store\Api\StoreRepositoryInterfaceFactory $storeRepositoryFactory,
                                \Magento\Store\Model\WebsiteRepository $websiteRepository,
                                \Magento\Store\Model\Website $website,
                                \Magento\Framework\App\State $state,
                                \Magento\Config\Model\ResourceModel\Config $resourceConfig
    )
    {
        $this->storeView = $storeView;
        $this->storeRepositoryFactory = $storeRepositoryFactory;
        $this->websiteRepository = $websiteRepository;
        $this->website = $website;
        $this->config = require 'Config.php';
        $this->resourceConfig = $resourceConfig;
        try{
            $state->setAreaCode('adminhtml');
        }
        catch(\Magento\Framework\Exception\LocalizedException $e){
            // left empty
        }
    }


    /**
     * @param Setup\ModuleDataSetupInterface $setup
     * @param Setup\ModuleContextInterface $moduleContext
     */
    public function install(Setup\ModuleDataSetupInterface $setup, Setup\ModuleContextInterface $moduleContext)
    {

        //get id of website by name
        $_websiteId = $this->websiteRepository->get($this->config['website'])->getId();

        //get groups (stores in website)
        $_websiteGroups = $this->website->load($this->config['website'])->getGroups();

        //get id of group
        foreach ($_websiteGroups as $group){
            if($group->getName()==$this->config['groupName']){
                $_groupId = $group->getId();
                break;
            }
        }

        //add new store

        $newStore = $this->storeView->create();
        //check if view exists, if it does load and update
        $existingStoreId = $this->getExistingStoreId($this->config['newViewCode']);
        if($existingStoreId !=0){
            $newStore->load($existingStoreId);
        }
        $newStore->setName($this->config['newViewName']);
        $newStore->setCode($this->config['newViewCode']);
        $newStore->setWebsiteId($_websiteId);
        $newStore->setGroupId($_groupId); // GroupId is a Store ID (in adminhtml terms)
        $newStore->setSortOrder($this->config['newViewPriority']);
        $newStore->setIsActive(true);
        $newStore->save();

        //Change name of default store
        $defaultStore = $this->storeView->create();
        $defaultStore->load('default');
        $defaultStore->setName($this->config['defaultStoreName']);
        $defaultStore->save();

        $this->resourceConfig->saveConfig("general/locale/code", "de_DE", "stores", $newStore->getId());
    }

    /**
     * @param $storeCode string
     * @return int
     */
    public function getExistingStoreId($storeCode){
        $storeRepository = $this->storeRepositoryFactory->create();
        $stores=$storeRepository->getList();
        foreach($stores as $store){
            if($store->getCode()==$storeCode){
                return $store->getId();
                break;
            }
        }
        return 0;
    }
}
