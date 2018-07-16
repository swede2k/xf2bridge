<?php namespace culv3r\XF2Bridge;

/**
 * Contracts and Exceptions
 */
use XenForo_Model;
use culv3r\XF2Bridge\Contracts\TemplateInterface;
use culv3r\XF2Bridge\Contracts\VisitorInterface;
use culv3r\XF2Bridge\Contracts\UserInterface;
use culv3r\XF2Bridge\Exceptions\XenforoAutoloaderException;

/**
 * Default XF2Bridge
 * Implementations
 */
use culv3r\XF2Bridge\Template\Template;
use culv3r\XF2Bridge\Visitor\Visitor;
use culv3r\XF2Bridge\User\User;

/**
 * Required XenForo Classes
 */
use XenForo_Autoloader;
use XenForo_Session;
use XenForo_Options;



class XF2Bridge
{
    /**
     * Xenforo Option Id for the boards base url
     */
    const XENFORO_OPTION_BASE_URL = 'boardUrl';

    /**
     * Default language id for Xenforo
     */
    const XENFOROBRIDGE_DEFAULT_LANGUAGE_ID = 1;

    /**
     * Absolute Path to Xenforo Directory
     * (ex. /home/username/www/forums/ )
     *
     * @var string
     */
    protected $xenforoDirectoryPath;

    /**
     * Base Url to Xenforo Application
     * (ex. http://example.com/forums | http://example.com)
     *
     * @var string
     */
    protected $xenforoBaseUrl;


    /**
     * @var VisitorInterface
     */
    protected $visitor;

    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * Bootstrap XF2Bridge
     *
     *
     * @param $xenforoDirectoryPath
     * @param null|string $xenforoBaseUrl
     * @throws XenforoAutoloaderException
     */
    public function __construct($xenforoDirectoryPath)
    {
        $this->xenforoDirectoryPath = $xenforoDirectoryPath;

        //Bootstrap Xenforo App
        $this->bootstrapXenforo($this->xenforoDirectoryPath);
    }

    /**
     * Bootstrap Xenforo Application and Start a Public Session
     *
     * @param string $directoryPath
     */
    protected function bootstrapXenforo($directoryPath)
    {
    /** 
     * @var  $fileDir 
     */

    $fileDir = $directoryPath;
    require( $fileDir . '/src/XF.php' );

    \XF::start($fileDir);

    $app = \XF::setupApp('XF\Pub\App');
    $app->start();
    //XenForo_Session::startPublicSession();
    }

    /**
     * Get all Xenforo Options
     *
     * @return mixed|XenForo_Options
     * @throws \Zend_Exception
     */
    public function getXenforoOptions()
    {
        if(!$this->xenforoOptions instanceof XenForo_Options)
        {
            $this->xenforoOptions = \XF::app()::get('options');
        }
        return $this->xenforoOptions;
    }

    /**
     * Get Xenforo Option by id
     *
     * @param string $id
     * @return mixed|null
     */
    public function getXenforoOptionById($id)
    {
        return $this->getXenforoOptions()->get($id);
    }

    /**
     * Attempts to load Xenforo_Autoloader.php throws exception if
     * unable to find or load.
     *
     * @param string $xenforoDirectory - Full path to Xenforo Directory
     * @return bool
     * @throws XenforoAutoloaderException
     */
	protected function loadXenAutoloader($xenforoDirectory)
	{
		$path = $xenforoDirectory. '/library/XenForo/Autoloader.php';

		$autoloader = include_once($path);

		if(!$autoloader)
		{
			throw new XenforoAutoloaderException('Could not load XenForo_Autoloader.php check path');
		}
	}

    /**
     * Retrieve Visitor Class
     *
     * @return VisitorInterface
     */
    public function retrieveVisitor()
    {
        if(!$this->visitor instanceof VisitorInterface)
        {
            $this->setVisitor(new Visitor);
        }

        return $this->visitor;
    }

    /**
     * Set a new Visitor implementation
     *
     * @param VisitorInterface $visitor
     */
    public function setVisitor(VisitorInterface $visitor)
    {
        $this->visitor = $visitor;
    }

    /**
     * Gets singleton instance of Xenforo_Visitor
     *
     * @return \XenForo_Visitor
     */
    public function getVisitor()
    {
        return $this->retrieveVisitor()->getCurrentVisitor();
    }

    /**
     * Get Xenforo Bridge Visitor Object
     * (use instead of geting Xenforo_Visitor class)
     *
     * @return VisitorInterface
     */
    public function getVisitorObject()
    {
        return $this->retrieveVisitor();
    }

    /**
     * Checks if current visitor is banned
     *
     * @return boolean
     */
    public function isBanned()
    {
        return (bool)$this->retrieveVisitor()->isBanned();
    }

    /**
     * Checks if current visitor is an Admin
     *
     * @return boolean
     */
	public function isAdmin()
	{
		return $this->retrieveVisitor()->isAdmin();
	}

    /**
     * Checks if visitor is a Super Admin
     *
     * @return boolean
     */
	public function isSuperAdmin()
	{
		return $this->retrieveVisitor()->isSuperAdmin();
	}

    /**
     * Checks if visitor is currently logged in
     *
     * @return boolean
     */
	public function isLoggedIn()
	{
		return $this->retrieveVisitor()->isLoggedIn();
	}

    /**
     * Retrieve the current Visitors User id
     *
     * @return mixed
     */
    public function getVisitorUserId()
    {
       return $this->getVisitor()->getUserId();
    }
    /**
     * Checks if visitor has a particular permission
     *
     * @param $group - permission group
     * @param $permission - permission
     * @return mixed
     */
    public function hasPermission($group,$permission)
    {
        return $this->retrieveVisitor()->hasPermission($group,$permission);
    }

    /**
     * Return current implementation or set Default User
     *
     * @return UserInterface
     */
    public function retrieveUser()
    {
        if(!$this->user instanceof UserInterface)
        {
            $this->setUser(new User);
        }

        return $this->user;
    }

    /**
     * Set current implementation of User
     *
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Return current User implementation
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->retrieveUser();
    }

    /**
     * Find User by Id
     *
     * @param $id
     * @return array
     */
	public function getUserById($id)
	{
		return $this->getUser()->getUserById($id);
	}

	/**
	 * Retrieve Xenforo User by Email
	 *
	 * If no user is found returns empty array
	 *
	 * @param $email
	 * @return array
	 */
	public function getUserByEmail($email)
	{
		return $this->getUser()->getUserByEmail($email);
	}

    /**
     * Get Xenforo User by Username - if no user is found returns empty array
     *
     * @param $name
     * @return array
     */
	public function getUserByName($name)
	{
		return $this->getUser()->getUserByUsername($name);
	}


    /**
     * Login and set user session to user id (No Validation is used on this method)
     *
     * @param (int) $user
     * @param bool|false $remember
     * @param bool|true $log
     * @return mixed
     */
    public function loginAsUser($user, $remember = false,$log = true)
    {
        // Set Remember Cookie
        if($remember)
        {
            /* @var \XenForo_Model_User */
            $this->getXenforoModel('XenForo_Model_User')->setUserRememberCookie($user);
        }

        //Log IP
        if($log)
        {
            /* @var XenForo_Model_Ip */
            $this->getXenforoModel('XenForo_Model_Ip')->logIp($user,'user',$user,'login');
        }

        $this->changeUserSession($user);

        return $user;
    }

    /**
     * Changes the users session to the corresponding user id
     * use this method with caution
     *
     * @param $userId
     */
    protected function changeUserSession($userId)
    {
        //delete current session
        $this->getXenforoModel('XenForo_Model_User')->deleteSessionActivity(0, $_SERVER['REMOTE_ADDR']);

        $this->getSession()->changeUserId($userId);
        $this->getVisitor()->setup($userId);
    }

    /**
     * @param $model
     * @return XenForo_Model
     * @throws \XenForo_Exception
     */
    public function getXenforoModel($model)
    {
        return XenForo_Model::create($model);
    }

    /**
     * Retrieves XenForo Session
     *
     * @return mixed
     * @throws \Zend_Exception
     */
    public function getSession()
    {
        return \XF::app()::get('session');
    }
}
