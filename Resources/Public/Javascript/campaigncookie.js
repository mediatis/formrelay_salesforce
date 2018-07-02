jQuery(document).ready(function() {
	console.log(formrelay_salesforce_campaign_number);
	console.log(jQuery.cookie('salesforceCampaignNumber'));
  if (formrelay_salesforce_campaign_number !== undefined && formrelay_salesforce_campaign_number !== '') {
	  if (typeof jQuery.cookie == 'function' && (jQuery.cookie('salesforceCampaignNumber') == undefined || jQuery.cookie('salesforceCampaignNumber') == '')) {
		  jQuery.cookie('salesforceCampaignNumber', formrelay_salesforce_campaign_number, { path: '/' });
	  }
  }
});