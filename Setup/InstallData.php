<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MagentoEse\LumaDESetup\Setup;

use Magento\Framework\Setup;


class InstallData implements Setup\InstallDataInterface
{
    protected $storeView;

    protected $websiteRepository;

    protected $groupRepository;

    protected $website;

    public function __construct(\Magento\Store\Model\Store $storeView,
                                \Magento\Store\Model\WebsiteRepository $websiteRepository,
                                \Magento\Store\Model\Website $website


    )
    {
        $this->storeView = $storeView;
        $this->websiteRepository = $websiteRepository;
        $this->website = $website;
        $this->config = require 'Config.php';
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
        $this->storeView->setName($this->config['newViewName']);
        $this->storeView->setCode($this->config['newViewCode']);
        $this->storeView->setWebsiteId($_websiteId);
        $this->storeView->setGroupId($_groupId); // GroupId is a Store ID (in adminhtml terms)
        $this->storeView->setSortOrder($this->config['newViewPriority']);
        $this->storeView->setIsActive(true);
        $this->storeView->save();
    }
}
