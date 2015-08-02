<?php

/**
 * @author: ranjani 
 * @category    tests
 * @package     selenium
 * @subpackage  tests
 * 
 * Test suite to test features in http://community.spiceworks.com/ 
 */

class SpiceWorksTest extends PHPUnit_Extensions_Selenium2TestCase {
    protected $siteURL = "http://community.spiceworks.com/";
    protected $waitPeriod = 100;
    
    //array of all DOM elements
    protected $uiMapElements= array (
        "Login" => 
        array(
            "page1" =>
            array(
                "fields" =>
                array(
                        "lastName" => "//*[@id='registration_last_name']",
                        "firstName" => "//*[@id='registration_first_name']",
                        "email" => "//*[@id='registration_email']"
                ),
                "links" =>
                array(
                    "signIn" => "//a[@href='/login']"
                )
            ),
            "page2" =>
            array(
                "fields" =>
                array(
                    "email" => "//*[@id='login_email']",
                    "password" => "//*[@id='login_password']"
                ),
                "buttons" =>
                array(
                    "logIn" => "//button[contains(., 'Log In')]"
                )
            )
        ),
        "NetworkingPage"=>
        array(
            "links" =>
            array(
                "resources" => "//a[contains(., 'Resources')]",
                "vendorPages" => "//a[contains(., 'Vendor Pages')]",
                "seeAll" => "//a[contains(., 'See all')]",
            ),
            
            "seeAllTabs" =>
            array( 
                "groups" =>
                array(
                    "groups" => "//div[@id='primary']//a[contains(., 'Groups')]",  
                    "groupsCisco" =>  "//a[contains(. , 'Cisco')]"
                ),
                "resources" =>
                array(
                    "resources" => "//div[@id='primary']//a[contains(., 'Resources')]",
                    "whitePaperImage" => "//div[@class='subject'][contains(.,'Seven Habits of Highly')]/../img"
                )
            )    
            
        )
    );    
       

    //data array
    protected $dataForUIMap = array(
        "Login" => 
        array(
            "page1" =>
            array(
                "fields" =>
                array( 
                    "lastName" => "testing123",
                    "firstName" => "spiceworks",
                    "email" => "spiceworks.testing123@gmail.com"
                )   
           ),     
            "page2" =>
            array(
                "fields" =>
                array(
                    "email" => "spiceworks.testing123@gmail.com",
                    "password" => "Spiceworks123"
                )
            )    
        )
      
    );    

    
    public static $browsers = array(
        array(
            "name" => "Firefox",
            "browserName" => "firefox",
        ),
    );
 
    /**
     * setup will be run for all our tests
     */
    protected function setUp()  {
        $this->setBrowserUrl($this->siteURL);
        $this->setHost('127.0.0.1');
        $session = $this->prepareSession();
        $session->currentWindow()->maximize();
        
    } 
 
    /** 
     * <p> Function to test if the Login section right </p>
     * <p> Steps </p>
     * <ol>
     * <li> Navigate to Login Page </li>
     * <li> Login using valid credentials </li>
     * <li> Check if the user was able to login </li>
     * </ol>
     * 
     *@test
     *@group 
     */
    public function test_VerifySpiceWorksLogin()  {
 
        //open url
        $this->url($this->siteURL);
        sleep(1);
        
        $url = $this->loginSpiceWorksCommunity();
        $url = parse_url($url);
        $this->assertRegExp("/start/", $url['path'], "Community start page not displayed after login");  
    }    
    /** 
     * <p> Function to test if Resources Header is present in Networking page </p>
     * <p> Steps </p>
     * <ol>
     * <li> Navigate to Networking page under Categories in Start page </li>
     * <li> Verify if 'Resources' header is present </li>
     * </ol>
     * 
     *@test
     *@group 
     */
    public function test_VerifyResourcesHeaderInNetworkingPage()  {
 
        //open url
        $this->url($this->siteURL);
        
        //Login
        $url = $this->loginSpiceWorksCommunity();
        $this->url($url);
        $this->timeouts()->implicitWait($this->waitPeriod);
        
        //click on Categories, Networking and check if the correct url is opened
        $this->clickLinkContains("Categories");
        $this->timeouts()->implicitWait($this->waitPeriod);
        $this->clickLinkContains("Networking");
        $this->timeouts()->implicitWait($this->waitPeriod); 
        $urlNetworking = $this->url();
        $url = parse_url($urlNetworking);
        $this->assertRegExp("/networking/", $url['path'], "link Networking does not work right in the start page");  
        $this->url($urlNetworking);
        
        //verify Resources tab
        $this ->verifyPathInPage('Networking', 'Resources', $this->uiMapElements['NetworkingPage']['links']['resources']);
        
    }  
    /** 
     * <p> Function to test if "Vendor Pages" exists on the Networking page under Resources  </p>
     * <p> Steps </p>
     * <ol>
     * <li> Navigate to Networking page </li>
     * <li> Verify if 'Vendor Pages ' is present under Resources </li>
     * </ol>
     * 
     *@test
     *@group 
     */
    public function test_VerifyVendorPagesUnderResourcesInNetworkingPage()  {
        //open url
        $this->url($this->siteURL);
        
        //Login
        $url = $this->loginSpiceWorksCommunity();
        
        $url = $this->siteURL. 'networking?source=navbar-subnav';
        $this->url($url);
        $this->clickLinkContains("Resources");
        $this->timeouts()->implicitWait($this->waitPeriod);
        //verify Resources tab
        $this ->verifyPathInPage('Networking', 'Vendor Pages', $this->uiMapElements['NetworkingPage']['links']['vendorPages']);
        
    }
    /** 
     * <p> Function to test if  'Cisco' appears in the 'Groups' when 'See all' is clicked in Networking page </p>
     * <p> Steps </p>
     * <ol>
     * <li> Navigate to Networking page </li>
     * <li> Click "see all" </li>
     * <li> Click on Groups tab </li>
     * <li> Verify if 'Cisco' appears in the groups </li>
     * </ol>
     * 
     *@test
     *@group 
     */
     public function test_VerifyCompanyIsInGroups()  {
        //open url
        $this->url($this->siteURL);
        
        //Login
        $url = $this->loginSpiceWorksCommunity();
        
        $url = $this->siteURL. 'networking?source=navbar-subnav';
        $this->url($url);
        $this->timeouts()->implicitWait($this->waitPeriod);
        $this->clickLinkContains("see all");
        
        $url = parse_url($this->url());
        $this->assertRegExp("/topics/", $url['path'], "Link 'See All' does not work right in the Networking page");  
        
        $this->clickElement($this->uiMapElements['NetworkingPage']['seeAllTabs']['groups']['groups']);
        $this->timeouts()->implicitWait($this->waitPeriod);
        //verify Resources tab
        $this ->verifyPathInPage('Groups Tab', 'Cisco', $this->uiMapElements['NetworkingPage']['seeAllTabs']['groups']['groupsCisco']);
    }
    
    /** 
     * <p> Function to test if the Whitepaper icon exists next to "Seven Habits of Highly Successful MSPs". </p>
     * <p> Steps </p>
     * <ol>
     * <li> Navigate to Networking page </li>
     * <li> Click "see all" </li>
     * <li> Click on Resources tab </li>
     * <li> Verify if the white paper image appears next to "Seven Habits of Highly Successful MSPs"</li>
     * </ol>
     * 
     *@test
     *@group 
     */
    
    public function test_VerifyWhitePaperIconInResourcesTab()  {
        //open url
        $this->url($this->siteURL);
        
        //Login
        $url = $this->loginSpiceWorksCommunity();
        $this->url($this->siteURL. 'networking/topics?sort=popular');
        
        //open resources tab
        $this->clickElement($this->uiMapElements['NetworkingPage']['seeAllTabs']['resources']['resources']);
        $this->timeouts()->implicitWait($this->waitPeriod);
        
        //check if white paper image is presnt
        $this ->verifyPathInPage('Resources', 'WhitePaperImage', $this->uiMapElements['NetworkingPage']['seeAllTabs']['resources']['whitePaperImage']);
    }

    /**
     * Helper Methods
     */

    /**
     * Function to login to the SpiceWorks community page
     * @return type
     */
    private function loginSpiceWorksCommunity(){

        $this->loginPage1(); 
        sleep(1);
        $this ->verifyPathInPage('login', 'email', $this->uiMapElements['Login']['page2']['fields']['email']);
        $this ->loginPage2();
        sleep(1);
        return $this->url();
    }
    
    /**
     * Function to login to page1 of the SpiceWorks community page
     * @return type
     */
    private function loginPage1(){

        $this-> fillForm($this->uiMapElements['Login']['page1']['fields'], $this->dataForUIMap ['Login']['page1']['fields'] );
        $this->clickElement($this->uiMapElements['Login']['page1']['links']['signIn']);


    }
    /**
     * Function to login to page2 of the SpiceWorks community page
     * @return type
     */
    private function loginPage2(){
        $this-> fillForm($this->uiMapElements['Login']['page2']['fields'], $this->dataForUIMap ['Login']['page2']['fields'] );
        $this->clickElement($this->uiMapElements['Login']['page2']['buttons']['logIn']);
        
    }
    /**
     * Function to check if the given path exisits in page
     * $page - Name of the page 
     * $searchElement - Element you are looking for
     * $xPath - xpath for the search element
     * @return type
     */
    private function verifyPathInPage($page, $searchElement, $xPath){
        try{
            $this->byXPath($xPath);
        } catch (Exception $ex) {
            $this->fail("Page" . $page .  " does not have the element you are looking for : " . $searchElement);
        }
    }
    
    /**
     * Function to fill a form
     * @param type $fields - Xpath
     * @param type $dataForFields - data
     */
    private function fillForm($fields, $dataForFields){

        foreach(array_merge_recursive($fields, $dataForFields) as $field => $xPathData){
            $elem = $this->byXPath($xPathData[0]);
            $elem ->click();
            $elem->clear();
            $this ->keys($xPathData[1]);  
        }
    }
    /**
     * Function to click on a link that contains an element : $elementName
     * @param type $elementName
     */
    private function clickLinkContains($elementName){
        $this->clickElement("//a[contains(., '" . $elementName . "')]");
    }
    /**
     * Function to click on an element 
     * $xpath - xpath to click on 
     */
    private function clickElement($xpath){
        $this->byXPath($xpath)->click();
    }

}  

    
    