<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public License, 
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can 
 * obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * Class to be used to register and run hooks in Stud.IP. The difference to
 * NotificationCenter is that the HookCenter doesn't handle simple events but
 * it serves objects of hookclasses the registered function can interact with.
 * 
 * So if you just want to tell that you created a user, just fire the event with
 * the NotificationCenter, but if you want to display a user and want to give 
 * plugins or any other code the ability to add links or input fields or whatever
 * you should use the HookCenter. The used hookclass gathers the icons, inputfields
 * or whatever you want and you can use the object of type hookclass to display
 * these information to the user-page.
 * 
 * Example for running a hook:
 *     $actions = new DisplayOnlineUserActionHook("onlinelist");
 *     $actions = HookCenter::run("DisplayOnlineUserActionHook", $actions);
 *     foreach ($actions->getSubNavigation() as $action) {
 *         echo '<a href="'.URLHelper::getLink($action->getURL()).'">'.htmlReady($action->getTitle()).'</a> ';
 *     }
 * 
 * Example for registering a callback to a hook (should be run before the the hook is run):
 *     HookCenter::register("DisplayOnlineUserActionHook", function ($navigation) {
 *         $nav = new DisplayOnlineUserActionHook(_("Nachricht verfassen"), URLHelper::getURL("sms_send.php"));
 *         $nav->setImage(Assets::image_path("icons/16/blue/mail.png"), array('title' => _("Nachricht verfassen")));
 *         $navigation->addSubNavigation("messaging", $nav);
 *     });
 * 
 * You can see that the hookclass is the mediator between the registered function
 * and the code that has called the hook. To know as a programmer what exactly the
 * code calling the hook is expecting, you only need to know about the hookclass.
 * Just have a look at the class, at the documentation of the class and/or at 
 * the output of the static method #getHookDescription() of the hookclass that 
 * usually describes what the hook is doing and how the registered function can 
 * interact with the hookclass.
 */
class EventCenter 
{
    static protected $registered_events = array(); //the hooks and registered callables
    
    /**
     * Registers a callback function for the case that the given hook is run.
     * @param string $hookclass : name of the hookclass is the name of the hook itself.
     * @param callable|closure $callable : your callback. The first argument of the 
     * callback-function will be an instance of type $hookclass your callback
     * receives from the HookCenter when the hook is run. See documentation of
     * the $hookclass to know how your callback can interact with the hook.
     */
    static public function register($eventname, $callable) 
    {
        $eventname = strtolower($eventname);
        self::$registered_events[$eventname][] = $callable;
    }
    
    /**
     * Runs a hook. If there are any functions registered (via #register) those 
     * functions will get called and an instance of $hookclass will be given to
     * that function. The function can then retrieve information of the instance
     * of $hookclass, just do some stuff or even interact with the class and give
     * information to the instance back. This method returns the instance that
     * has run through all registered functions.
     * @param type $hookclass : the name of the class that defined the behaviour 
     * of thee hook. The class is more or less identic with the hook. An instance 
     * of $hookclass will get passed to all registered functions and those functions
     * can interact with this instance, call public methods and even give 
     * information to the object. The code that has called the Hook (called #run)
     * gets the object back and can retrieve information.
     * @param hookclass $instance : an optional instance of $hookclass. If you want 
     * the instance that is passed to the registered functions not to come freshly
     * out of the constructor you can pass your own instance of $hookclass here 
     * that may contain special information.
     * @return \hookclass : instance of the $hookclass. If you gave in a second 
     * argument the returned object will be exactly that instance, otherwise a 
     * new instance will be created. But in both cases the instance was handled by
     * all registered functions and can contain more information or a different 
     * state than before.
     */
    static public function run($eventname, $params = null) 
    {
        $eventname = strtolower($eventname);
        foreach (self::$registered_events as $registered_event => $callables) {
            if ($registered_event === $eventname) {
                foreach ($callables as $callable) {
                    if (is_callable($callable)) {
                        call_user_func($callable, $params);
                    } else {
                        throw new Exception(sprintf("%s is not a callable.", $callable));
                    }
                }
            }
        }
    }
}

