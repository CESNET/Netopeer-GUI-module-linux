<?php

namespace FIT\Bundle\ModuleLinuxBundle\Controller;

use FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders\DnsResolverServerType;
use FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders\DnsResolverType;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\DnsResolver;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\DnsResolverSearch;
use FIT\NetopeerBundle\Controller\ModuleControllerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\DnsResolverServer;

class DnsResolverController extends ModuleController
{
	/**
	 * @Route("/sections/{key}/{module}/dns/{subsection}/detail/", name="dns_resolver")
	 * @Template("FITModuleLinuxBundle:DnsResolver:dnsResolver.html.twig")
     *
     * @param int $key
     * @param string $module
     * @param string $subsection
     *
     * @return array|null|\SimpleXMLIterator|RedirectResponse|Response
	 */
	public function dnsResolverAction($key, $module = "system", $subsection = "dns-resolver")
	{
        if ($this->getRequest()->getMethod() == 'POST') {
            $res = $this->handleNtpForm($key, $module, $subsection);
            if ($res !== true) {
                return $res;
            }
        }

        $twigArr = $this->getLinuxBundleTwigArr($key, $module, $subsection);

        if (array_key_exists('stateArr', $twigArr)) {
            if (isset($twigArr['stateArr']->clock)) {
                $form = $this->createForm(new DnsResolverType(), new DnsResolver(), array('features' => $this->getFeatures($key)));
            } else {
                $form = $this->createForm(new DnsResolverType(), DnsResolver::createFromXml($twigArr['stateArr']->{'dns-resolver'}), array('features' => $this->getFeatures($key)));
            }
            $twigArr['formConfigDnsResolver'] = $form->createView();
        }

        return $twigArr;
    }

    /**
     * @Route("/ajax/{key}/dns/server/modal", name="get_dns_server_modal_form")
     * @return Response
     */
    public function getServerDnsModalFormAjaxAction($key)
    {
        if (($serverNumber = $this->getRequest()->get('serverNumber')) != null) {
            $dns = new DnsResolver();
            $dns->getServer()[$serverNumber] = DnsResolverServer::createFromServerNumber($serverNumber);
            $form = $this->createForm(new DnsResolverType(), $dns, array('features' => $this->getFeatures($key)));
        }
        else {
            $request = $this->getRequest();
            $dns = new DnsResolver();
            $form = $this->createForm(new DnsResolverType(), $dns, array('features' => $this->getFeatures($key)));
            $form->handleRequest($request);
        }

        return $this->render('FITModuleLinuxBundle:DnsResolver:dnsServerModal.html.twig', array( "formConfigDnsResolver" => $form->createView()) );
    }

    /**
     * @Route("/ajax/dns/server/", name="get_dns_server_form")
     * @return Response
     */
    public function getServerDnsFormAjaxAction()
    {
        $request = $this->getRequest();
        $dns = new DnsResolver();
        $form = $this->createForm(new DnsResolverType(), $dns);
        $form->handleRequest($request);
        return $this->render('FITModuleLinuxBundle:DnsResolver:dnsServerForm.html.twig', array( "formConfigDnsResolver" => $form->createView()) );
    }

    /**
     * @param int $key
     * @param string $module
     * @param string $subsection
     * @return array|bool|Response
     */
    private function handleNtpForm($key, $module, $subsection) {
        $request = $this->getRequest();
        $dns = new DnsResolver();
        $form = $this->createForm(new DnsResolverType(), $dns);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            $twigArr = $this->getLinuxBundleTwigArr($key, $module, $subsection);
            $twigArr['formConfigDnsResolver'] = $form->createView();

            return $twigArr;
        }
        else {
            $this->saveValidData($key, $module, $subsection, $form);
            return true;
        }
    }
}
