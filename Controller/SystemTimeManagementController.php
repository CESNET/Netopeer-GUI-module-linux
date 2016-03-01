<?php

namespace FIT\Bundle\ModuleLinuxBundle\Controller;

use FIT\NetopeerBundle\Controller\ModuleControllerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\ConfigClockForm;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\NewNodeClockForm;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\ConfigNtpForm;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\ConfigNtpServerForm;


class SystemTimeManagementController extends \FIT\NetopeerBundle\Controller\ModuleController {
        /**
	 * @Route("/sections/{key}/{module}/timemanagement/{subsection}/detail/", name="system_time_management")
	 * @Template("FITModuleLinuxBundle:SystemTimeManagement:systemTimeManagement.html.twig")
	 */
	public function systemTimeManagementAction($key, $module = "system", $subsection = "clock") {
            $res = $this->prepareDataForModuleAction("FITModuleLinuxBundle", $key, $module, $subsection);

            if ($res instanceof RedirectResponse) {
                //$res->setTargetUrl($this->generateUrl('system_time_management'));
		return $res;
            } 
		
            $twigArr = $this->getTwigArr();
            
            if (array_key_exists('stateArr', $twigArr) && isset($twigArr['stateArr']->clock)) {               
                if (isset($twigArr['stateArr']->clock->{'timezone-utc-offset'})) {
                    $twigArr['formConfigClock'] = $this->getConfigClockFormFromXml($twigArr['stateArr']->clock); 
                }
                else if (isset($twigArr['stateArr']->clock->{'timezone-name'})) {
                    $twigArr['formNewNodeClock'] = $this->getNewNodeClockFormFromXml($twigArr['stateArr']->clock);
                }
            }
            else if (array_key_exists('stateArr', $twigArr) && isset($twigArr['stateArr']->ntp)) {
                $twigArr['formConfigNtp'] = $this->getConfigNtpFormFromXml($twigArr['stateArr']->ntp); 
            }
            
            return $twigArr;              
        }
        
        /**
         * @Route("/ajax/ntp/server/modal", name="get_server_modal_form")
         * 
         */
        public function getServerModalFormAjaxAction() {
            $post_vals = $this->getRequest()->get('configDataForm');                     
            $configNtpServerForm = $this->getConfigNtpServerFromPost($post_vals);          
            return $this->render('FITModuleLinuxBundle:SystemTimeManagement:ntpServerModal.html.twig', array( "formConfigNtpServer" => $configNtpServerForm) );
            
        }
        
        /**
         * @Route("/ajax/ntp/server/", name="get_server_form")
         * 
         */
        public function getServerFormAjaxAction() {
            $post_vals = $this->getRequest()->get('configDataForm');                     
            $configNtpServerForm = $this->getConfigNtpServerFromPost($post_vals);          
            return $this->render('FITModuleLinuxBundle:SystemTimeManagement:ntpServerForm.html.twig', array( "formConfigNtpServer" => $configNtpServerForm) );
            
        }
        
        private function getConfigClockFormFromXml($clockXml) {
            $clockForm = new ConfigClockForm();

            $clockForm->timezoneOffsetValue = $clockXml->{'timezone-utc-offset'};
            $clockForm->timezoneOffsetXpath = "timezone-utc-offset_--system--xmlns:clock--xmlns:timezone-utc-offset";
            $clockForm->formName = "configDataForm";
            
            return $clockForm;
        }
        
        private function getNewNodeClockFormFromXml($clockXml) {
            $clockForm = new NewNodeClockForm();
            $timezone = new \DateTimeZone($clockXml->{'timezone-name'});
                       
            $clockForm->timezoneOffsetValue = $timezone->getOffset(new \DateTime("now"))/60;
            $clockForm->timezoneOffsetValueInputName = "newNodeForm[value0_--system--xmlns:clock--xmlns:timezone-utc-offset]";
            $clockForm->timezoneOffsetLabel = "timezone-utc-offset";
            $clockForm->timezoneOffsetLabelInputName = "newNodeForm[label0_--system--xmlns:clock--xmlns:timezone-utc-offset]";
            $clockForm->parentNode = "--xmlns:system--xmlns:clock";
            $clockForm->parentNodeInputName = "newNodeForm[parent]";
            $clockForm->formName = "newNodeForm";
            
            return $clockForm;
        }
        
        private function getConfigNtpFormFromXml($ntpXml) {
            $ntpForm = new ConfigNtpForm();
            
            $ntpForm->enabledValue = $ntpXml->enabled;
            $ntpForm->enabledXpath = "enabled_--system--xmlns:ntp--xmlns:enabled";
            $index = 1;
            foreach($ntpXml->server as $server) {
                $ntpServerForm = new ConfigNtpServerForm();
                $ntpServerForm->nameValue = $server->name;
                $ntpServerForm->nameXpath = "name_--system--xmlns:ntp--xmlns:server?".$index."!--xmlns:name";
                $ntpServerForm->addressValue = $server->udp->address;
                $ntpServerForm->addressXpath = "address_--system--xmlns:ntp--xmlns:server?".$index."!--xmlns:udp--xmlns:address";
                //port
                $ntpServerForm->associationValue = $server->association;
                $ntpServerForm->associationXpath = "association_--system--xmlns:ntp--xmlns:server?".$index."!--xmlns:association";
                $ntpServerForm->iburstValue = $server->iburst;
                $ntpServerForm->iburstXpath = "iburst_--system--xmlns:ntp--xmlns:server?".$index."!--xmlns:iburst";
                $ntpServerForm->preferValue = $server->prefer;
                $ntpServerForm->preferXpath = "prefer_--system--xmlns:ntp--xmlns:server?".$index."!--xmlns:prefer";
                $ntpServerForm->formName = "configDataForm";
                $ntpForm->servers[] = $ntpServerForm;
                $index++;
            }        
            $ntpForm->formName = "configDataForm";
            
            return $ntpForm;
        } 
        
        private function getConfigNtpServerFromPost($post_vals) {
            $ntpServerForm = new ConfigNtpServerForm();
                       
            $ntpServerForm->nameXpath = $post_vals["nameXpath"];
            $ntpServerForm->nameValue = $post_vals[$ntpServerForm->nameXpath];
            $ntpServerForm->addressXpath = $post_vals["addressXpath"];
            $ntpServerForm->addressValue = $post_vals[$ntpServerForm->addressXpath];  
            //port
            $ntpServerForm->formName = "configDataForm";
            
            return $ntpServerForm;
        }
        
        /*
         *  <?xml version="1.0"?> 
         *      <system xmlns="urn:ietf:params:xml:ns:yang:ietf-system"> 
         *          <ntp eltype="container" config="true" description="Configuration of the NTP client." iskey="false"> 
         *              <enabled eltype="leaf" config="true" type="boolean" description="Indicates that the system should attempt to synchronize the system clock with an NTP server from the 'ntp/server' list." default="true" iskey="false">
         *                  false
         *              </enabled> 
         *              <server eltype="list" config="true" description="List of NTP servers to use for system clock synchronization. If '/system/ntp/enabled' is 'true', then the system will attempt to contact and utilize the specified NTP servers." key="name" iskey="false">
         *                  <name eltype="leaf" config="true" type="string" description="An arbitrary name for the NTP server." iskey="true">
         *                      server-1
         *                  </name> 
         *                  <udp eltype="container" config="true" description="Contains UDP-specific configuration parameters for NTP." iskey="false">
         *                      <address eltype="leaf" config="true" type="union" description="The address of the NTP server." mandatory="true" iskey="false">
         *                          0.rhel.pool.ntp.org
         *                      </address> 
         *                  </udp> 
         *                  <association-type eltype="leaf" config="true" type="enumeration" enumval="server|peer|pool" description="The desired association type for this NTP server." default="server" iskey="false">
         *                      server
         *                  </association-type>
         *                  <iburst eltype="leaf" config="true" type="boolean" description="Indicates whether this server should enable burst synchronization or not." iskey="false">
         *                      true
         *                  </iburst>
         *                  <prefer eltype="leaf" config="true" type="boolean" description="Indicates whether this server should be preferred or not." iskey="false">
         *                      false
         *                  </prefer> 
         *              </server> 
         *              <server eltype="list" config="true" description="List of NTP servers to use for system clock synchronization. If '/system/ntp/enabled' is 'true', then the system will attempt to contact and utilize the specified NTP servers." key="name" iskey="false"> 
         *                  <name eltype="leaf" config="true" type="string" description="An arbitrary name for the NTP server." iskey="true">
         *                      server-2
         *                  </name>
         *                  <udp eltype="container" config="true" description="Contains UDP-specific configuration parameters for NTP." iskey="false"> 
         *                      <address eltype="leaf" config="true" type="union" description="The address of the NTP server." mandatory="true" iskey="false">
         *                          1.rhel.pool.ntp.org
         *                      </address> 
         *                  </udp>
         *  <association-type eltype="leaf" config="true" type="enumeration" enumval="server|peer|pool" description="The desired association type for this NTP server." default="server" iskey="false">server</association-type>
         *  <iburst eltype="leaf" config="true" type="boolean" description="Indicates whether this server should enable burst synchronization or not." iskey="false">true</iburst>
         *  <prefer eltype="leaf" config="true" type="boolean" description="Indicates whether this server should be preferred or not." iskey="false">false</prefer> 
         * </server> 
         * <server eltype="list" config="true" description="List of NTP servers to use for system clock synchronization. If '/system/ntp/enabled' is 'true', then the system will attempt to contact and utilize the specified NTP servers." key="name" iskey="false"> 
         * <name eltype="leaf" config="true" type="string" description="An arbitrary name for the NTP server." iskey="true">server-3</name> <udp eltype="container" config="true" description="Contains UDP-specific configuration parameters for NTP." iskey="false"> 
         * <address eltype="leaf" config="true" type="union" description="The address of the NTP server." mandatory="true" iskey="false">2.rhel.pool.ntp.org</address> </udp> 
         * <association-type eltype="leaf" config="true" type="enumeration" enumval="server|peer|pool" description="The desired association type for this NTP server." default="server" iskey="false">server</association-type> 
         * <iburst eltype="leaf" config="true" type="boolean" description="Indicates whether this server should enable burst synchronization or not." iskey="false">true</iburst> 
         * <prefer eltype="leaf" config="true" type="boolean" description="Indicates whether this server should be preferred or not." iskey="false">false</prefer>
         *  </server> 
         * <server eltype="list" config="true" description="List of NTP servers to use for system clock synchronization. If '/system/ntp/enabled' is 'true', then the system will attempt to contact and utilize the specified NTP servers." key="name" iskey="false"> 
         *  <name eltype="leaf" config="true" type="string" description="An arbitrary name for the NTP server." iskey="true">
         *      server-4</name> <udp eltype="container" config="true" description="Contains UDP-specific configuration parameters for NTP." iskey="false"> 
         * <address eltype="leaf" config="true" type="union" description="The address of the NTP server." mandatory="true" iskey="false">3.rhel.pool.ntp.org</address> 
         * </udp> <association-type eltype="leaf" config="true" type="enumeration" enumval="server|peer|pool" description="The desired association type for this NTP server." default="server" iskey="false">server</association-type> 
         * <iburst eltype="leaf" config="true" type="boolean" description="Indicates whether this server should enable burst synchronization or not." iskey="false">true</iburst> 
         * <prefer eltype="leaf" config="true" type="boolean" description="Indicates whether this server should be preferred or not." iskey="false">false</prefer>
         *  </server> </ntp> </system>
         * 
         * 
         * 
         * 
         */
}
