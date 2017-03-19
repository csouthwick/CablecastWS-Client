<?php

/*
 * Classes that users will either need to create as parameter for a function or
 * will recieve as a return value from a function.
 */

/**
 * Parameter object for the AdvancedShowSearch function
 * 
 * $ChannelID is required, but all other properties are optional and default to
 * having no restriction on the search. If none of the optional properties are
 * set, the AdvancedShowSearch function will return all shows. It is important
 * to note that most Cablecast servers only return the top 50 results by default
 * without additional configuration.
 */
class AdvancedShowSearch {
    /** @var int $ChannelID ID of the channel to search */
    var $ChannelID;

    /** @var string $searchString Text to search for */
    var $searchString = "";

    /**
     * @var DateTime $eventDate Restrict search based on a date.
     * Must be used in conjunction with $dateComparator. 
     */
    var $eventDate;

    /**
     * @var string $dateComparator How the search should be restricted by a date.
     * Must be used in conjunction with $dateComparator.
     * Valid values are  "=", "<", ">", "<=", ">=", or "!="
     */
    var $dateComparator = "";

    /** @var int $restrictToCategoryID Restrict search based on a category ID */
    var $restrictToCategoryID = 0;

    /** @var int $restrictToProducerID Restrict search based on a producer ID */
    var $restrictToProducerID = 0;

    /** @var int $restrictToProjectID Restrict search based on a project ID */
    var $restrictToProjectID = 0;

    /** @var bool $displayStreamingShowsOnly Restrict to streaming shows only */
    var $displayStreamingShowsOnly = FALSE;

    /** @var bool $searchOtherSites Get results from other sites too */
    var $searchOtherSites = FALSE;

    /**
     * Parameter object for the AdvancedShowSearch function
     * 
     * @param int $ChannelID Required - ID of the channel to search
     */
    function __construct(int $ChannelID) {
        $this->ChannelID = $ChannelID;
        $this->eventDate = new DateTime();
    }

}

class ShowInformation {
    /** @var int */
    var $ShowID;

    /** @var string */
    var $Title;

    /** @var string */
    var $Category;

    /** @var string */
    var $Comments;

    /** @var string[] */
    var $CustomFields;

    /** @var DateTime */
    var $EventDate;

    /** @var string */
    var $Producer;

    /** @var string */
    var $Project;

    /** @var int */
    var $TotalSeconds;

    /** @var DateInterval */
    var $Duration;

    /** @var string */
    var $StreamingFileURL;
}

class SiteSearchResults {
    /** @var boolean */
    var $IsRemoteSite;

    /** @var string */
    var $SiteName;

    /** @var string */
    var $SiteDescription;

    /** @var string */
    var $SiteAddress;

    /** @var string */
    var $SiteCity;

    /** @var string */
    var $SiteState;

    /** @var string */
    var $SiteZipCode;

    /** @var string */
    var $SitePhone;

    /** @var string */
    var $SiteEmail;

    /** @var string */
    var $SiteWeb;

    /** @var ShowInformation[] */
    var $Shows;
}

class ScheduleInformation {
    /** @var int */
    var $ScheduleID;

    /** @var int */
    var $ShowID;

    /** @var string */
    var $ShowTitle;

    /** @var DateTime */
    var $StartTime;

    /** @var DateTime */
    var $EndTime;

    /** @var DateInterval */
    var $Duration;
}

class Producer {
    var $ProducerID; //int
    var $Name; //string
}

class Category {
    var $CategoryID; //int
    var $Name; //string
}

class Project {
    var $ProjectID; //int
    var $Name; //string
    var $Description; //string
    var $HasPodcast; //boolean
    var $PodcastName; //string
    var $PodcastDescription; //string
    var $PodcastUrl; //string
}

class Channel {
    var $ChannelID; //int
    var $Name; //string
}
