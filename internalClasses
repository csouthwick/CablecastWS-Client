<?php

/*
 * Classes that are used internally by the CablecastWS client wrapper.
 * These, as well as a few classes from userClasses.php, are required for the
 * SOAP client classmap.
 */

/*
 * Request classes
 */

/**
 * @ignore Internal use only
 */
class GetShowInformation {
    var $ShowID; //int
}

/**
 * @ignore Internal use only
 */
class GetScheduleInformation {
    var $ChannelID; //int
    var $FromDate; //dateTime
    var $ToDate; //dateTime
    var $restrictToShowID; //int
}

/**
 * @ignore Internal use only
 */
class GetProducers {
    var $ChannelID; //int
}

/**
 * @ignore Internal use only
 */
class GetCategories {
    var $ChannelID; //int
}

/**
 * @ignore Internal use only
 */
class GetProjects {
    var $ChannelID; //int
}

/**
 * @ignore Internal use only
 */
class SimpleShowSearch {
    var $ChannelID; //int
    var $searchString; //string
}

/**
 * @ignore Internal use only
 */
class GetLiveURL {
    var $ChannelID; //int
}


/*
 * Response classes
 */

/**
 * @ignore Internal use only
 */
class WSVersionResponse {
    var $WSVersionResult; //string
}

/**
 * @ignore Internal use only
 */
class DataVersionResponse {
    var $DataVersionResult; //string
}

/**
 * @ignore Internal use only
 */
class GetShowInformationResponse {
    var $GetShowInformationResult; //ShowInfo
}

/**
 * @ignore Internal use only
 */
class GetScheduleInformationResponse {
    var $GetScheduleInformationResult; //ArrayOfScheduleInfo
}

/**
 * @ignore Internal use only
 */
class GetProducersResponse {
    var $GetProducersResult; //ArrayOfProducer
}

/**
 * @ignore Internal use only
 */
class GetCategoriesResponse {
    var $GetCategoriesResult; //ArrayOfCategory
}

/**
 * @ignore Internal use only
 */
class GetProjectsResponse {
    var $GetProjectsResult; //ArrayOfProject
}

/**
 * @ignore Internal use only
 */
class GetChannelsResponse {
    var $GetChannelsResult; //ArrayOfChannel
}

/**
 * @ignore Internal use only
 */
class SimpleShowSearchResponse {
    var $SimpleShowSearchResult; //ArrayOfSiteSearchResult
}

/**
 * @ignore Internal use only
 */
class AdvancedShowSearchResponse {
    var $AdvancedShowSearchResult; //ArrayOfSiteSearchResult
}

/**
 * @ignore Internal use only
 */
class GetLiveURLResponse {
    var $GetLiveURLResult; //string
}

/*
 * Data classes
 */

/**
 * @ignore Internal use only
 */
class ShowInfo {
    var $ShowID; //int
    var $Title; //string
    var $Category; //string
    var $Comments; //string
    var $CustomFields; //ArrayOfCustomField
    var $EventDate; //dateTime
    var $Producer; //string
    var $Project; //string
    var $TotalSeconds; //int
    var $StreamingFileURL; //string
}

/**
 * @ignore Internal use only
 */
class CustomField {
    var $Name; //string
    var $Value; //string
}

/**
 * @ignore Internal use only
 */
class ScheduleInfo {
    var $ScheduleID; //int
    var $ShowID; //int
    var $ShowTitle; //string
    var $StartTime; //dateTime
    var $EndTime; //dateTime
}

/**
 * @ignore Internal use only
 */
class SiteSearchResult {
    var $IsRemoteSite; //boolean
    var $SiteName; //string
    var $SiteDescription; //string
    var $SiteAddress; //string
    var $SiteCity; //string
    var $SiteState; //string
    var $SiteZipCode; //string
    var $SitePhone; //string
    var $SiteEmail; //string
    var $SiteWeb; //string
    var $Shows; //ArrayOfShowInfo
}

/*
 * Array classes
 * Initialize these variables to consistently deal with arrays without needing
 * to check for unset/null variables.
 */

/**
 * @ignore Internal use only
 */
class ArrayOfCustomField {
    var $CustomField = []; //CustomField
}

/**
 * @ignore Internal use only
 */
class ArrayOfScheduleInfo {
    var $ScheduleInfo = []; //ScheduleInfo
}

/**
 * @ignore Internal use only
 */
class ArrayOfProducer {
    var $Producer = []; //Producer
}

/**
 * @ignore Internal use only
 */
class ArrayOfCategory {
    var $Category = []; //Category
}

/**
 * @ignore Internal use only
 */
class ArrayOfProject {
    var $Project = []; //Project
}

/**
 * @ignore Internal use only
 */
class ArrayOfChannel {
    var $Channel = []; //Channel
}

/**
 * @ignore Internal use only
 */
class ArrayOfSiteSearchResult {
    var $SiteSearchResult = []; //SiteSearchResult
}

/**
 * @ignore Internal use only
 */
class ArrayOfShowInfo {
    var $ShowInfo = []; //ShowInfo
}
