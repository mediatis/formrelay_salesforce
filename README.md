# Introduction
This extension is base on the [mediatis/formrelay](https://github.com/mediatis/formrelay) package and you and use it to get the data of any TYPO3/form (or other form extensions that are supported by formrelay) to Salesforce. We use the Web2Lead API of Salesforce, so all your triggers you defined in SFDC will be fired.

You should checkout  https://github.com/mediatis/formrelay to find more detail how use the plugin.

# Installation

`composer require mediatis/formrelay-salesforce`

# Support
If you have any question, please contact us voehringer@mediatis.de

# Submit bug reports or feature requests

Look at the [Issues](https://github.com/mediatis/formrelay_salesforce/issues)
for what has been planned to be implemented in the (near) future.

# Setup

All basic settings, explained in EXT:formrelay, (including the overwrite mechanics) apply to this extension as well.  

## plugin.tx_formrelay_salesforce.settings.enabled

Default: `0`.

Set to `1` to enable the extension.

## plugin.tx_formrelay_salesforce.settings.salesForceUrl

Default: `https://webto.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8`.

Set the URL of the SFDC Web-To-Lead API.

## plugin.tx_formrelay_salesforce.settings.campaignNumber.deleteCookieAfterSending

Default: `0`.

Set the flag whether or not to delete the cookie that stored the campaign number.

## plugin.tx_formrelay_salesforce.settings.defaults.oid 

Default: none.

## plugin.tx_formrelay_salesforce.settings.defaults.lead_source

Default: `Web - TYPO3 formrelay`.

## plugin.tx_formrelay_salesforce.settings.defaults.encoding

Default: `UTF-8`.

## plugin.tx_formrelay_salesforce.settings.defaults.debug

Default: none.

## plugin.tx_formrelay_salesforce.settings.defaults.debugEmail

Default: none.

## plugin.tx_formrelay_salesforce.settings.fields.mapping

There is a default mapping for standard SFDC fields set.


## plugin.tx_formrelay_salesforce.settings.values.mapping
