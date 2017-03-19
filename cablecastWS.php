<?php

require_once "internalClasses.php";
require_once "userClasses.php";

/**
 * Cablecast Web Service SOAP Client wrapper
 * 
 * Designed and tested with WSVersion 2.0.1 and DataVersion 2.0.0, but may be
 * compatible with other versions.
 */
class CablecastWS {
    var $soapClient;
    private static $classmap = array(
        'WSVersionResponse' => 'WSVersionResponse',
        'DataVersionResponse' => 'DataVersionResponse',
        'GetShowInformation' => 'GetShowInformation',
        'GetShowInformationResponse' => 'GetShowInformationResponse',
        'ShowInfo' => 'ShowInfo',
        'ArrayOfCustomField' => 'ArrayOfCustomField',
        'CustomField' => 'CustomField',
        'GetScheduleInformation' => 'GetScheduleInformation',
        'GetScheduleInformationResponse' => 'GetScheduleInformationResponse',
        'ArrayOfScheduleInfo' => 'ArrayOfScheduleInfo',
        'ScheduleInfo' => 'ScheduleInfo',
        'GetProducers' => 'GetProducers',
        'GetProducersResponse' => 'GetProducersResponse',
        'ArrayOfProducer' => 'ArrayOfProducer',
        'Producer' => 'Producer',
        'GetCategories' => 'GetCategories',
        'GetCategoriesResponse' => 'GetCategoriesResponse',
        'ArrayOfCategory' => 'ArrayOfCategory',
        'Category' => 'Category',
        'GetProjects' => 'GetProjects',
        'GetProjectsResponse' => 'GetProjectsResponse',
        'ArrayOfProject' => 'ArrayOfProject',
        'Project' => 'Project',
        'GetChannelsResponse' => 'GetChannelsResponse',
        'ArrayOfChannel' => 'ArrayOfChannel',
        'Channel' => 'Channel',
        'SimpleShowSearch' => 'SimpleShowSearch',
        'SimpleShowSearchResponse' => 'SimpleShowSearchResponse',
        'ArrayOfSiteSearchResult' => 'ArrayOfSiteSearchResult',
        'SiteSearchResult' => 'SiteSearchResult',
        'ArrayOfShowInfo' => 'ArrayOfShowInfo',
        'AdvancedShowSearch' => 'AdvancedShowSearch',
        'AdvancedShowSearchResponse' => 'AdvancedShowSearchResponse',
        'GetLiveURL' => 'GetLiveURL',
        'GetLiveURLResponse' => 'GetLiveURLResponse'
    );

    function __construct($url) {
        $this->soapClient = new SoapClient($url, array(
            "classmap" => self::$classmap,
            "soap_version" => SOAP_1_2,
            "features" => SOAP_SINGLE_ELEMENT_ARRAYS
        ));
    }

    /**
     * Returns the version of the Cablecast web service being accessed
     * 
     * @return string
     */
    function WSVersion() {
        return $this->soapClient->WSVersion()->WSVersionResult;
    }

    /**
     * Returns the version of the Cablecast web service's data structures
     * 
     * @return string
     */
    function DataVersion() {
        return $this->soapClient->DataVersion()->DataVersionResult;
    }

    private function secondsToInterval(int $seconds) {
        $str = "PT";
        $str .= intdiv($seconds, (60 * 60)) . "H";
        $str .= intdiv($seconds, 60) % 60 . "M";
        $str .= ($seconds % 60) . "S";

        return new DateInterval($str);
    }

    private function unwrapShowInfo(ShowInfo $show) {
        $out = new ShowInformation();
        $out->ShowID = $show->ShowID;
        $out->Title = $show->Title;
        $out->Category = $show->Category;
        $out->Comments = $show->Comments;
        $out->CustomFields = array();
        foreach ($show->CustomFields->CustomField as $customField) {
            $out->CustomFields[$customField->Name] = $customField->Value;
        }
        $out->EventDate = new DateTime($show->EventDate);
        $out->Producer = $show->Producer;
        $out->Project = $show->Project;
        $out->TotalSeconds = $show->TotalSeconds;
        $out->Duration = $this->secondsToInterval($show->TotalSeconds);
        $out->StreamingFileURL = $show->StreamingFileURL;
        return $out;
    }

    /**
     * Returns information about a particular show.
     * 
     * @return ShowInformation
     */
    function GetShowInformation(int $ShowID) {
        $params = new GetShowInformation();
        $params->ShowID = $ShowID;
        $result = $this->soapClient->GetShowInformation($params);
        return $this->unwrapShowInfo($result->GetShowInformationResult);
    }

    /**
     * Returns an array of schedule data on ChannelID between FromDate and ToDate.
     * 
     * Setting "restrictToShowID" to a positive, non-zero value will return
     * schedule information containing only the specified ShowID.
     * 
     * @return ScheduleInformation[]
     * 
     * @internal Due to Cablecast's method of searching, a full day must be
     * subtracted from the intended end date. My guess is that this was done so
     * that searching from today to today (with time assumed 0:00:00 for both)
     * would return the full day's schedule even though this is an error.
     * Ex: Searching from today 0:00:00 to today 9:00:00 will actually
     * return today 0:00:00 to tomorrow 9:00:00. To correct this error,
     * search from today 0:00:00 to ((today 9:00:00) - 1 day)
     * Also does not get currently playing show if start time of program is
     * before the FromDate
     */
    function GetScheduleInformation(int $ChannelID, DateTime $FromDate, DateTime $ToDate, int $restrictToShowId = 0) {
        $params = new GetScheduleInformation();
        $params->ChannelID = $ChannelID;
        $params->FromDate = $FromDate->format("c");
        $ToDate->modify("-1 day"); //Compensate for off-by-one error in ToDate
        $params->ToDate = $ToDate->format("c");
        $params->restrictToShowID = $restrictToShowId;

        $schedule = $this->soapClient->GetScheduleInformation($params)->GetScheduleInformationResult->ScheduleInfo;
        
        $out = array();
        foreach ($schedule as $scheduleItem) {
            $temp = new ScheduleInformation();
            $temp->ScheduleID = $scheduleItem->ScheduleID;
            $temp->ShowID = $scheduleItem->ShowID;
            $temp->ShowTitle = $scheduleItem->ShowTitle;
            $temp->StartTime = new DateTime($scheduleItem->StartTime);
            $temp->EndTime = new DateTime($scheduleItem->EndTime);
            $temp->Duration = date_diff($temp->StartTime, $temp->EndTime, true);
            $out[] = $temp;
        }

        return $out;
    }

    /**
     * Returns an array of show producers who create content on the specified channel.
     * 
     * @return Producer[]
     */
    function GetProducers(int $ChannelID) {
        $params = new GetProducers();
        $params->ChannelID = $ChannelID;
        return $this->soapClient->GetProducers($params)->GetProducersResult->Producer;
    }

    /**
     * Returns an array of show categories for the specified channel. 
     * 
     * @return Category[]
     */
    function GetCategories(int $ChannelID) {
        $params = new GetCategories();
        $params->ChannelID = $ChannelID;
        return $this->soapClient->GetCategories($params)->GetCategoriesResult->Category;
    }

    /**
     * Returns an array of projects on the specified channel.
     * 
     * @return Project[]
     */
    function GetProjects(int $ChannelID) {
        $params = new GetProjects();
        $params->ChannelID = $ChannelID;
        return $this->soapClient->GetProjects($params)->GetProjectsResult->Project;
    }

    /**
     * Returns a list of all the channels on this Cablecast server.
     * 
     * @return Channel[]
     */
    function GetChannels() {
        return $this->soapClient->GetChannels()->GetChannelsResult->Channel;
    }

    private function unwrapSearchResults(array $siteResults) {
        $out = array();

        foreach ($siteResults as $site) {
            $temp = new SiteSearchResults();
            $temp->IsRemoteSite = $site->IsRemoteSite;
            $temp->SiteName = $site->SiteName;
            $temp->SiteDescription = $site->SiteDescription;
            $temp->SiteAddress = $site->SiteAddress;
            $temp->SiteCity = $site->SiteCity;
            $temp->SiteState = $site->SiteState;
            $temp->SiteZipCode = $site->SiteZipCode;
            $temp->SitePhone = $site->SitePhone;
            $temp->SiteEmail = $site->SiteEmail;
            $temp->SiteWeb = $site->SiteWeb;
            $temp->Shows = array();
            foreach ($site->Shows->ShowInfo as $show) {
                $temp->Shows[] = $this->unwrapShowInfo($show);
            }
            $out[] = $temp;
        }
        return $out;
    }

    /**
     * Returns information about shows whose titles contain matches in the search string
     * 
     * @param int $ChannelID Required
     * @param string $searchString Empty string (the default value) returns all shows
     * @return SiteSearchResults[] Array of SiteSearchResults will only contain
     * one object if not searching multiple sites. The SiteSearchResults object
     * contains information about the site with the array of search results for
     * that site on the SiteSearchResults->Shows property.
     */
    function SimpleShowSearch(int $ChannelID, string $searchString = "") {
        $params = new SimpleShowSearch();
        $params->ChannelID = $ChannelID;
        $params->searchString = $searchString;

        $result = $this->soapClient->SimpleShowSearch($params);
        $result = $result->SimpleShowSearchResult->SiteSearchResult;

        return $this->unwrapSearchResults($result);
    }

    /**
     * Returns show information at the specified site (and optionally other
     * sites) for shows which match the given criteria. 
     * 
     * @param AdvancedShowSearch $AdvancedShowSearch The AdvancedShowSearch
     * class encapsulates all of the input parameters for this function.
     * 
     * @return SiteSearchResults[] Array of SiteSearchResults will only contain
     * one object if not searching multiple sites. The SiteSearchResults object
     * contains information about the site with the array of search results for
     * that site on the SiteSearchResults->Shows property.
     */
    function AdvancedShowSearch(AdvancedShowSearch $AdvancedShowSearch) {
        $AdvancedShowSearch->eventDate = $AdvancedShowSearch->eventDate->format("c");
        $result = $this->soapClient->AdvancedShowSearch($AdvancedShowSearch);
        $result = $result->AdvancedShowSearchResult->SiteSearchResult;

        return $this->unwrapSearchResults($result);
    }

    /**
     * Returns the Live IP Video URL for the specified channel, if the URL exists.
     * 
     * @return string
     */
    function GetLiveURL(int $ChannelID) {
        $params = new GetLiveURL();
        $params->ChannelID = $ChannelID;
        return $this->soapClient->GetLiveURL($params)->GetLiveURLResult;
    }
}
