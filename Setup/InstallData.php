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
    }




    public function install(Setup\ModuleDataSetupInterface $setup, Setup\ModuleContextInterface $moduleContext)
    {
        $_website = 'base';
        $_groupName = 'Main Website Store';
        $_newViewCode = 'luma_de';
        $_newViewName = 'German';
        $_priority = 5;

        //get id of website by name
        $_websiteId = $this->websiteRepository->get($_website)->getId();

        //get groups (stores in website)
        $_websiteGroups = $this->website->load($_websiteId)->getGroups();

        //get id of group
        foreach ($_websiteGroups as $group){
            if($group->getName()==$_groupName){
                echo $_groupName;
                $_groupId = $group->getId();
                echo $_groupId;
                break;
            }
        }
        $this->storeView->setName($_newViewName);
        $this->storeView->setCode($_newViewCode);
        $this->storeView->setWebsiteId($_websiteId);
        $this->storeView->setGroupId($_groupId); // GroupId is a Store ID (in adminhtml terms)
        $this->storeView->setSortOrder($_priority);
        $this->storeView->setIsActive(true);
        $this->storeView->save();
    }
}
