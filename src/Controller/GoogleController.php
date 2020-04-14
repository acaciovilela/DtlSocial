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
use DtlSocial\Service\Social\Google;
use DtlSocial\Entity\Google as GoogleEntity;

class GoogleController extends AbstractActionController {

    /**
     *
     * @var EntityManager
     */
    protected $entityManager;

    /**
     *
     * @var Google 
     */
    protected $googleService;

    public function indexAction() {
        $user = $this->identity();

        $google = $this->getEntityManager()->getRepository(GoogleEntity::class)
                ->findOneBy(['user' => $user]);

        if ($google) {
            $profile = $this->getGoogleService()->getProfile($google);
        } else {
            $profile = false;
        }

        return new ViewModel([
            'profile' => $profile,
        ]);
    }

    public function oauthAction() {
        $code = $this->params()->fromQuery('code');

        if (!isset($code)) {
            return $this->redirect()->toRoute('dtl-admin/dtl-social');
        }

        $signIn = $this->getGoogleService()->signIn($code);

        if (!$signIn) {
            return $this->redirect()->toRoute('dtl-admin/dtl-social');
        }

        return $this->redirect()->toRoute('dtl-admin/dtl-social/google');
    }

    public function disconnectAction() {
        $user = $this->identity();

        $google = $this->getEntityManager()->getRepository(GoogleEntity::class)
                ->findOneBy(['user' => $user]);

        if ($google) {
            $revoke = $this->getGoogleService()->revoke($google);
            if ($revoke) {
                $this->getEntityManager()->remove($google);
                $this->getEntityManager()->flush();
            }
        }

        return $this->redirect()->toRoute('dtl-admin/dtl-social');
    }

    public function getEntityManager(): EntityManager {
        return $this->entityManager;
    }

    public function setEntityManager(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
        return $this;
    }

    public function getGoogleService(): Google {
        return $this->googleService;
    }

    public function setGoogleService(Google $googleService) {
        $this->googleService = $googleService;
        return $this;
    }

}
