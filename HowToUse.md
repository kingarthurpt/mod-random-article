**This documentation is out of date and will be updated soon to include the options of v1.4**

# Introduction #
This module is pretty simple to use but this page will hopefully give more details to anyone that needs extra information about how to use it.



# Basic Options #
  * ## Joomla categories ##
This module will show random articles from the categories that you choose in this option.
In Joomla 2.5 you can select multiple categories by pressing SHIFT or CRTL.

  * ## Include Subcategories ##
If you activate this option, every article from all subcategories of the categories that you previously selected will be considered and will eventually appear on this module.

  * ## Number of articles ##
This is the number of articles that will be displayed inside the module instance. It will always select different articles, therefore the same article won't be displayed multiple times, even if there aren't more articles to be selected.

  * ## K2 categories ##
This option will show random K2 items from the categories that you choose in this option.
In Joomla 2.5 you can select multiple categories by pressing SHIFT or CRTL.
This option will only be visible if you have K2 installed and enabled.

  * ## Include Subcategories ##
This works the same way as the previous identical option but will include K2 subcategories.
This option will only be visible if you have K2 installed and enabled.

  * ## Number of articles ##
This works the same way as the previous identical option but will define the number of K2 articles.
This option will only be visible if you have K2 installed and enabled.


# Display Options #

  * ## Display Title ##
If you activate this option, the title of the random article will be displayed on this module.

  * ## Link Title ##
If you activate this option, the title of the random article will have an anchor tag that will link the title to the random article's page.

  * ## Display Introtext ##
If you activate this option, the introtext of the random article will be displayed on this module. Introtext is all the article's content that exists before the readmore separator.

  * ## Limit Introtext ##
Here you can choose if you want to limit the amount words or characters that appear in the introtext.

  * ## Limit count ##
This is the number that defines the amount of words or characters that you want to be displayed.

  * ## Display intro image ##
This option will display the image set under 'Article Manager > Images and Links > Intro Image' inside the introtext container.

  * ## Display Readmore ##
If you activate this option, an anchor tag will be added to the article after the introtext with a text saying something like "click here to read more"

  * ## Display Fulltext ##
If you activate this option, all the content that exists after the readmore separator of the random article will be displayed.

  * ## Display full article image ##
This option will display the image set under 'Article Manager > Images and Links > Full Article Image' inside the Fulltext container.

# Advanced Options #

  * ## Module Class Suffix ##
This option is very common in every Joomla extension. You can use it to assign a specific CSS class name to the module. This is helpful if you need multiple instances of this module with different layouts or if you want to use alternate class names that you can recall easier.

  * ## Caching ##
This option should be disabled (set to 'no caching') to avoid problems related with caching. If you choose 'On - Progressive Caching ' under 'Global Configurations' -> 'Cache Settings' this will also cause problems. It will only work correctly if your 'Cache Settings' are 'Off' or 'On - Conservative Caching';

  * ## Cache Time ##
If you choose to enable the module cache in the previous option, this is the time in minutes that it takes to Joomla refresh the content displayed by the module.

  * ## Display HTML5 tags ##
This option will convert the output tags to HTML5.

# Debug Options #

  * ## Display Warnings ##
This option will display warnings inside the module output to help you troubleshoot misconfiguration issues. For example a warning will be shown if you choose to display the fulltext of an article that doesn't have the fulltext.

  * ## Enable Logfile ##
This option will save all the module's data to a log file. This file will be saved in this directory: /tmp/mod\_random-article-debuglogfile.txt. You should enable this option if you found a bug and want to report it. This file contains all the information needed by the developer to know exactly why the bug is happening and should be always sent when opening an issue / sending an email.

  * ## Disable time restrictions ##
This option will disable time restrictions when picking random articles from the database. It should only be used to troubleshoot a problem that might occur. If this option is enabled the articles might have wrong URLs that point to pages 404.