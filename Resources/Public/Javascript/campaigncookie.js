jQuery(document).ready(function() {
  // Set Cookie if current page has a campaign number and no cookie exists yet
  if (typeof jQuery.cookie == 'function') {
    if (formrelay_salesforce_campaign_number !== undefined && formrelay_salesforce_campaign_number !== '') {
      var sfCookie = jQuery.cookie('sfCampaignNumber');
      if (sfCookie == undefined || sfCookie == '') {
        jQuery.cookie('sfCampaignNumber', formrelay_salesforce_campaign_number, { path: '/' });
      }
    }
  }
});