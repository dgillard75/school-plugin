<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 8/27/15
 * Time: 5:23 PM
 */


class LMS_UserProgress
{
    /** A complete list of units in this course. */
    protected $unitList;

    /** A complete list of units that the user has completed for this course. */
    protected $unitListProgress;

    /** ID of course we're getting progress data for. */
    protected $courseID;

    /** ID of user we're getting progress data for. */
    protected $userID;

    /** Determines if the user is allowed to access the course. */
    protected $canAccessCourse;

    /** If true, the user can only access the next available unit and all previously completed units. */
    public $userCanOnlyAccessNext;

    /** If true, we've stored the next item that the user can access (which might be false, hence this flag). */
    protected $nextUnitLoaded;

    /** The next unit to complete by the user (loaded by getNextPendingUnit()). */
    protected $nextUnit;


    /**
     * Create user progress object.
     *
     * @param Integer $courseID The ID of the course that we're checking.
     * @param Integer $userID The ID of the user that we're checking.
     */
    function __construct($courseID, $userID)
    {
        $this->courseID = $courseID;
        $this->userID = $userID;

        // Work out if access allowed
        $this->canAccessCourse = LMS_School::is_authorized($this->userID, $this->courseID);

        // Work out if want walled access to units
        $this->userCanOnlyAccessNext = true;

        $courseDetails = LMS_DBFunctions::retrieve_course($this->courseID);

        /**
         *
         * User Can only Access Next Unit in the following situations:
         *
         * 1. User completed current unit and next unit is active
         *
         */
        /**  if ($courseDetails && 'completion_wall' == $courseDetails->course_opt_completion_wall) {
            $this->userCanOnlyAccessNext = true;
        }
        **/

        // Get the next item user is allowed to access
        $this->nextUnitLoaded 	= false;
        $this->nextUnit 		= false;
    }


    /**
     * Determine if the user can access the course.
     * @return Boolean True if they can, false otherwise.
     */
    function canUserAccessCourse() {
        return $this->canAccessCourse;
    }


    /**
     * Load the full list of units for this course.
     */
    private function loadFullUnitList()
    {
        if ($this->unitList) {
            return;
        }

        // Get a list of all units for this course in absolute order

        // Always create an array - so we know if we've tried this or not.
        // If unitList is false, we've not tried to fill it yet.
        $this->unitList = array();


        // Convert into an ID => object list
        $items = LMS_DBFunctions::retrieve_full_lesson_list($this->courseID);
        if ($items)
        {
            foreach ($items as $item) {
                $this->unitList[$item[LESSONS_TBL_ID]]	= $item;
            }
        }
    }

    /**
     * Load a list of the units that the user has completed in this course.
     */
    private function loadUserUnitProgress()
    {
        if ($this->unitListProgress) {
            return;
        }

        // Always create an array - so we know if we've tried this or not.
        // If unitListProgress is false, we've not tried to fill it yet.
        $this->unitListProgress = array();

        // Convert into an ID => object list
        $items = LMS_DBFunctions::retrieve_user_progress($this->userID,$this->courseID);
        if ($items)
        {
            foreach ($items as $item) {
                $this->unitListProgress[$item["unit_id"]] = $item;
            }
        }
    }


    /**
     * Returns the details of the next unit that needs to be done by the user.
     *
     * @return Object The object if there's a next unit to complete, or false if no units,
     * not allowed to see course, or already completed the course.
     */
    private function getNextPendingUnit_fetch()
    {
        if (!$this->canUserAccessCourse()) {
            return false;
        }

        // Get list of units to choose from. Abort if no units in course.
        $this->loadFullUnitList();
        if (!$this->unitList) {
            return false;
        }

        // If no progress, then choose first item in list of course units.
        $this->loadUserUnitProgress();
        if (!$this->unitListProgress) {
            LMS_Log::print_r($this->unitList,__FUNCTION__);
            return current($this->unitList);
        }


        // Go through list of all items, and see what's not been done yet.
        foreach ($this->unitList as $unitID => $unitMeta)
        {
            if (!isset($this->unitListProgress[$unitID])) {
                //$unitMeta->data = get_post($unitID);
                return $unitMeta;
            }
        }

        // Done them already, so return nothing.
        return false;
    }

    /**
     * Returns the details of the next unit that needs to be done by the user.
     *
     * @return Object The object if there's a next unit to complete, or false if no units,
     * not allowed to see course, or already completed the course.
     */
    function getNextPendingUnit()
    {
        // Already fetched it
        if ($this->nextUnitLoaded) {
            return $this->nextUnit;
        }

        // Not loaded the next unit yet, so load it, cache it, and return it.
        $this->nextUnit = $this->getNextPendingUnit_fetch();
        $this->nextUnitLoaded = true;

        return $this->nextUnit;
    }


    /**
     * Quick function to check if the specified Unit has been completed by the user.
     * @param Integer $unitID The ID of the unit that's being checked.
     * @param Boolean True if the unit has been completed, false otherwise.
     */
    function isUnitCompleted($unitID)
    {
        if ($unitID < 1) {
            return false;
        }

        $this->loadUserUnitProgress();

        // Simply check if ID is in list of progress items.
        return (isset($this->unitListProgress[$unitID]));
    }

    function getCoursePercentageCompleted()
    {
        $percentage = 0;
        $this->loadFullUnitList();
        // If no units to complete, then not complete.
        if (!$this->unitList) {
            return $percentage;
        }

        $totalUnits = count($this->unitList);
        //LMS_Log::print_r($totalUnits,__FUNCTION__);
        $completed_units = 0;
        // Ensure all units in the course are complete
        foreach ($this->unitList as $aUnitObj)
        {
            //if unit is complted mark as completed.
            if ($this->isUnitCompleted($aUnitObj["id"])){
                $completed_units++;
            }
        }

        //LMS_Log::print_r($completed_units,__FUNCTION__);
        $percentage = ($completed_units / $totalUnits) * 100;
        return round($percentage,2);
    }

    /**
     * Checks if the specified unit was the last unit in the module to complete.
     *
     * @param Integer $unitID The ID of the unit that's being checked.
     * @param Boolean True if the module has been completed, false otherwise.
     */
    function isModuleCompleted($unitID)
    {
        $this->loadFullUnitList();

        // If no units to complete, then not complete.
        if (!$this->unitList) {
            return false;
        }

        // Get details for this particular unit
        $thisUnitDetails = $this->unitList[$unitID];
        $parentModuleID = $thisUnitDetails->parent_module_id;

        // Bad parent module. So not complete. Shouldn't be here, but
        // check anyway.
        if ($parentModuleID < 1) {
            return false;
        }

        // Get list of all units that are in same module
        $moduleList = array();
        foreach ($this->unitList as $aUnitID => $aUnitObj)
        {
            // Same module, so copy it to list
            if ($aUnitObj->parent_module_id == $parentModuleID) {
                $moduleList[$aUnitID] = $aUnitObj;
            }
        }

        // Ensure all units in the module are complete
        foreach ($moduleList as $aUnitObj)
        {
            if (!$this->isUnitCompleted($aUnitObj->unit_id)){
                return false;
            }
        }

        // Got this far, so all units in module should be complete.
        return true;
    }


    /**
     * Checks if the course is now complete.
     *
     * @param Boolean True if the course has been completed, false otherwise.
     */
    function isCourseCompleted()
    {
        $this->loadFullUnitList();

        // If no units to complete, then not complete.
        if (!$this->unitList) {
            return false;
        }

        // Ensure all units in the course are complete
        foreach ($this->unitList as $aUnitObj)
        {
            if (!$this->isUnitCompleted($aUnitObj->unit_id)){
                return false;
            }
        }

        return true;
    }

    function hasUserStartedCourse(){
        $this->loadUserUnitProgress();
        if ($this->unitListProgress) {
            return true;
        }

        return false;
    }

    /**
     * Quick function to check if the user can access the specified unit based on course settings.
     * @param Integer $unitID The ID of the unit that's being checked.
     * @param Boolean True if the unit can be accessed by this user, false otherwise.
     */
    function canUserAccessUnit($unitID)
    {
        // Invalid unit
        if ($unitID < 1) {
            return false;
        }

        // Not allowed to access course.
        if (!$this->canUserAccessCourse()) {
            return false;
        }

        // Allowed to access all
        if (!$this->userCanOnlyAccessNext) {
            return true;
        }

        //1st Check:

        // If no progress, then User can access 1st unit
        $this->loadUserUnitProgress();
        $next_and_previous_units = $this->getNextAndPreviousUnit($unitID);
        if (!$this->unitListProgress && !$next_and_previous_units["prev"]) {
            LMS_Log::print_r($next_and_previous_units,__FUNCTION__);
            return true;
        }

        // Allow access to anything that's complete. As user
        // has already seen it.
        if (isset($this->unitListProgress[$unitID])) {
            //LMS_Log::print_r($this->unitListProgress[$unitID],__FUNCTION__);
            return true;
        }

        // Now check for the next item after what's been completed.
        $nextUnit = $this->getNextPendingUnit();
        LMS_Log::print_r($nextUnit,__FUNCTION__);
        if ($nextUnit && $nextUnit[LESSONS_TBL_ID]== $unitID) {
            return true;
        }

        return false;
    }

    /**
     * Work out if the user can access the next and previous units based on the current unit, and return them.
     * @param Integer $unitID The unit to check for previous and next units.
     * @return Array The list of next and previous details.
     */
    function getNextAndPreviousUnit($unitID)
    {
        $details = array('next' => false, 'prev' => false);

        // Load all units.
        $this->loadFullUnitList();

        $prev = false;
        $next = false;
        $prevPrev = false;



        // Check this unit is in the list
        if (isset($this->unitList[$unitID]))
        {
            // Find previous and next by iterating through list of units
            foreach ($this->unitList as $unitObjID => $unitObj)
            {

                if ($prev == $unitID) {
                    $next = $unitObjID;
                    break;
                }

                $prevPrev = $prev;
                $prev = $unitObjID;

            }

            // Copy next and previous from search.
            $details['next'] = $next;
            $details['prev'] = $prevPrev;
        }

        return $details;
    }

}