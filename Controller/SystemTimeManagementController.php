<?php

namespace FIT\Bundle\ModuleLinuxBundle\Controller;

use FIT\Bundle\ModuleLinuxBundle\LinuxSectionNames;
use FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders\ClockType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\Clock;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\Ntp;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\NtpServer;
use FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders\NtpType;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;



class SystemTimeManagementController extends ModuleController
{
    /**
     * @Route("/sections/{key}/{module}/timemanagement/{subsection}/detail/", name="system_time_management")
     * @Template("FITModuleLinuxBundle:SystemTimeManagement:systemTimeManagement.html.twig")
     *
     * @param int $key
     * @param string $module
     * @param string $subsection
     *
     * @return array|null|\SimpleXMLIterator|RedirectResponse|Response
     */
	public function systemTimeManagementAction($key, $module = LinuxSectionNames::MODULE_SYSTEM_NAME, $subsection = LinuxSectionNames::SUBSECTION_CLOCK_NAME)
    {
        if ($this->getRequest()->getMethod() == 'POST') {
            $res = false;
            if ($subsection == LinuxSectionNames::SUBSECTION_CLOCK_NAME) {
                $res = $this->handleClockForm($key, $module, $subsection);
            } else if ($subsection == LinuxSectionNames::SUBSECTION_NTP_NAME) {
                $res = $this->handleNtpForm($key, $module, $subsection);
            }

            if ($res !== true) {
                return $res;
            }
        }

        $twigArr = $this->getLinuxBundleTwigArr($key, $module, $subsection);

        if (array_key_exists('stateArr', $twigArr) && $subsection == LinuxSectionNames::SUBSECTION_CLOCK_NAME) {
            $features = $this->getFeatures($key);
            $twigArr['featureTimezoneName'] = (array_search('timezone-name', $features) !== false);

            if (isset($twigArr['stateArr']->{LinuxSectionNames::SUBSECTION_CLOCK_NAME})) {
                $form = $this->createForm(new ClockType(), Clock::createFromXml($twigArr['stateArr']->{LinuxSectionNames::SUBSECTION_CLOCK_NAME}));
            } else {
                $form = $this->createForm(new ClockType(), new Clock());
            }
            $twigArr['formConfigClock'] = $form->createView();
        }
        else if (array_key_exists('stateArr', $twigArr) && $subsection == LinuxSectionNames::SUBSECTION_NTP_NAME) {
            $features = $this->getFeatures($key);
            $twigArr['featureNtp'] = (array_search('ntp', $features) !== false);

            if (isset($twigArr['stateArr']->{LinuxSectionNames::SUBSECTION_NTP_NAME})) {
                $form = $this->createForm(new NtpType(), Ntp::createFromXml($twigArr['stateArr']->{LinuxSectionNames::SUBSECTION_NTP_NAME}), array('features' => $features));
            } else {
                $form = $this->createForm(new NtpType(), new Ntp());
            }
            $twigArr['formConfigNtp'] = $form->createView();
        }

        return $twigArr;
    }

    /**
     * @Route("/ajax/{key}/ntp/server/modal", name="get_server_modal_form")
     * @param string $key
     * @return Response
     */
    public function getServerModalFormAjaxAction($key)
    {
        if (($serverNumber = $this->getRequest()->get('serverNumber')) != null) {
            $ntp = new Ntp();
            $ntp->getServer()[$serverNumber] = NtpServer::createFromServerNumber($serverNumber);
            $form = $this->createForm(new NtpType(), $ntp, array('features' => $this->getFeatures($key)));
        } else {
            $request = $this->getRequest();
            $ntp = new Ntp();
            $form = $this->createForm(new NtpType(), $ntp, array('features' => $this->getFeatures($key)));
            $form->handleRequest($request);
        }

        return $this->render('FITModuleLinuxBundle:SystemTimeManagement:ntpServerModal.html.twig', array( "formConfigNtp" => $form->createView()) );
    }
        
    /**
     * @Route("/ajax/ntp/server/get", name="get_server_form")
     * @return Response
     */
    public function getServerFormAjaxAction()
    {
        $request = $this->getRequest();
        $clock = new Ntp();
        $form = $this->createForm(new NtpType(), $clock);
        $form->handleRequest($request);
        return $this->render('FITModuleLinuxBundle:SystemTimeManagement:ntpServerForm.html.twig', array( "formConfigNtp" => $form->createView()) );
    }

    /**
     * @param int $key
     * @param string $module
     * @param string $subsection
     * @return array|bool|Response
     */
    private function handleClockForm($key, $module, $subsection) {
        $request = $this->getRequest();
        $clock = new Clock();
        $form = $this->createForm(new ClockType(), $clock);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            $twigArr = $this->getLinuxBundleTwigArr($key, $module, $subsection);
            $features = $this->getFeatures($key);
            $twigArr['featureTimezoneName'] = (array_search('timezone-name', $features) !== false);
            $twigArr['formConfigClock'] = $form->createView();

            return $twigArr;
        }
        else {
            $this->saveValidData($key, $module, $subsection, $form);
            return true;
        }
    }

    /**
     * @param string $key
     * @param string $module
     * @param string $subsection
     * @return array|bool|Response
     */
    private function handleNtpForm($key, $module, $subsection) {
        $request = $this->getRequest();
        $ntp = new Ntp();
        $form = $this->createForm(new NtpType(), $ntp);
        $form->handleRequest($request);
        
        if (!$form->isValid()) {
            $twigArr = $this->getLinuxBundleTwigArr($key, $module, $subsection);
            $features = $this->getFeatures($key);
            $twigArr['featureNtp'] = (array_search('ntp', $features) !== false);
            $twigArr['formConfigNtp'] = $form->createView();

            return $twigArr;
        }
        else {
            $this->saveValidData($key, $module, $subsection, $form);
            return true;
        }
    }
}
