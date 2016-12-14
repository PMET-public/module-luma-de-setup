<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MagentoEse\LumaDESetup\Setup;

use Magento\Framework\Setup;


class InstallData implements Setup\InstallDataInterface
{
    private $storeView;

    private $websiteRepository;

    private $website;

    private $state;

    public function __construct(\Magento\Store\Model\StoreFactory $storeView,
                                \Magento\Store\Model\WebsiteRepository $websiteRepository,
                                \Magento\Store\Model\Website $website,
                                \Magento\Framework\App\State $state


    )
    {
        $this->storeView = $storeView;
        $this->websiteRepository = $websiteRepository;
        $this->website = $website;
        $this->config = require 'Config.php';
        try{
            $state->setAreaCode('adminhtml');
        }
        catch(\Magento\Framework\Exception\LocalizedException $e){
            // left empty
        }
    }




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
    }
}
