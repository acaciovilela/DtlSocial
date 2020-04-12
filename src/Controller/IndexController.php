<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DtlSocial\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use DtlSocial\Entity\Facebook as FacebookEntity;
use DtlSocial\Service\Social\Facebook;

class IndexController extends AbstractActionController {

    /**
     *
     * @var EntityManager
     */
    protected $entityManager;

    public function indexAction() {

        $user = $this->identity();
        $sm = $this->getEvent()->getApplication()->getServiceManager();

        /**
         * Facebook
         */
        $facebook = $this->getEntityManager()->getRepository(FacebookEntity::class)
                ->findOneBy(['user' => $user]);
        $fbProfile = null;
        if ($facebook) {
            $fbService = $sm->get(Facebook::class);
            $fbProfile = $fbService->getProfile($facebook);
        }


        return new ViewModel([
            'facebook' => $fbProfile,
        ]);
    }

    public function getEntityManager(): EntityManager {
        return $this->entityManager;
    }

    public function setEntityManager(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
        return $this;
    }

}
