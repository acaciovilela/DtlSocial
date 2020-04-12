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
use DtlSocial\Entity\Google as GoogleEntity;
use DtlSocial\Service\Social\Facebook;
use DtlSocial\Service\Social\Google;

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
        
        /**
         * Google
         */
        $google = $this->getEntityManager()->getRepository(GoogleEntity::class)
                ->findOneBy(['user' => $user]);
        $gProfile = null;
        if ($google) {
            $gService = $sm->get(Google::class);
            $gProfile = $gService->getProfile($google);
        }


        return new ViewModel([
            'facebook' => $fbProfile,
            'google' => $gProfile,
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
