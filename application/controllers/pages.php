<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pages extends MY_Controller {

    public $public_methods = array();

    function __construct() {
        parent::__construct();
        parent::_create_cache();
    }

    function index() {
        $this->render_view(array(), 'home');
    }

    function safety() {
        $data = array();
        $data['title'] = 'Safety Tips';
        $data['keywords'] = 'Safety suggestions, legal disclaimers, business , individual, offering services';
        $data['description'] = "Always take care while posting and paying for item, safety is always recommended. Blendedd's safety suggestions when doing a service purchase";
        $this->render_view($data);
    }

    function prohibited() {
        $data = array();
        $data['title'] = 'Prohibited Items';
        $data['keywords'] = 'prohibited, prohibited goods, prohibited services, not allowed items, illegal content not allowed';
        $data['description'] = 'At blendedd, we ensure that user do not post any illegal or porn content. We protect the interest of genuine users.';
        $this->render_view($data);
    }

    function scams() {
        $data = array();
        $data['title'] = 'Avoiding Scams';
        $data['keywords'] = 'pay through blendedd, avoid wire transfer';
        $data['description'] = 'To avoid scams, blendedd warns users to pay through blendedd payment mode only. Paying outside blendedd website is never recommended.';
        $this->render_view($data);
    }

    function about_us() {
        $data = array();
        $data['title'] = 'About Us';
        $data['keywords'] = 'Rental,Services, blendedd, save money, earn money';
        $data['description'] = 'Search for rental items and services near to your  area in united states. Best services for renting out items and earning for what you are not using.';
        $this->render_view($data);
    }

    function how_does_it_work() {
        $data = array();
        $data['title'] = 'How Does It Work';
        $data['keywords'] = 'buying a service, renting an item, restaurant services, renting bike';
        $data['description'] = 'People can use services provided by business owner and also can rent out items on daily , weekly basis - united states of america';
        $this->render_view($data);
    }

    function faq() {
        $data = array();
        $data['title'] = 'FAQ';
        $data['keywords'] = 'why blendedd, service fee, frequently asked questions, why to rent out, earn money';
        $data['description'] = 'Blendedd provides services for renting out and selling services, for more details check our website';
        $this->render_view($data);
    }

    function contact_us() {
        $data = array();
        $data['title'] = 'Contact Us';
        $data['keywords'] = 'contact us, blendedd office, support, email to us';
        $data['description'] = 'People can contact us for details what to do and how to do, contact for more details';
        $this->render_view($data);
    }

    function stories() {
        $data = array();
        $data['title'] = 'Stories';
        $data['keywords'] = 'blendedd stories, people talk blendedd, extra income, my story Edward Mei';
        $data['description'] = 'What inspired me to start blendedd as online portal to earn and make people earn extra money, save money , earn money and help people to use un utilized things at home';
        $this->render_view($data);
    }

    function feedback() {
        $data = array();
        $data['title'] = 'Feedback';
        $data['keywords'] = 'feedback score, symbol, advantage of feedback, positive feedback';
        $data['description'] = 'Registered users can leave feedback for providers and owners. Users can leave feedback once they have registered and paid for item / service.';
        $this->render_view($data);
    }

    function privacy() {
        $data = array();
        $data['title'] = 'Privacy Policy';
        $data['keywords'] = 'protect information, data privacy, blendedd privacy policy';
        $data['description'] = "At blendedd, we keep all the data safe and confidential. We do not share user's data with any individual or organization for any purpose.";
        $this->render_view($data);
    }

    function terms() {
        $data = array();
        $data['title'] = 'Terms of Use';
        $data['keywords'] = 'user of the services, users outside US, Use of Rental items, Protional Material';
        $data['description'] = 'Terms of use is a legal fit document for buyer and renter both. It helps service provider and buyer both to know the legalaties';
        $this->render_view($data);
    }

    function page_not_found() {
        $data = array();
        $data['title'] = '404 Page Not Found';
        $this->render_view($data);
    }

}
