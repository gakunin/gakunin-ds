function getMatchIdPList(json, list){
	var newList = new Array();
	var index = 0;
	var matchFlg = false;
	
	for (var i=0; i<list.length; i++) {
		for (var j=0; j<json.length; j++) {
			if (list[i].entityid == json[j].entityID) {
				newList[index] = list[i];
				matchFlg = true;
				index++;
				break;
			}
		}
		for (var k=0; k<wayf_additional_idps.length; k++) {
			if (list[i].entityid == wayf_additional_idps[k].entityID) {
				newList[index] = list[i];
				matchFlg = true;
				index++;
				break;
			}
		}
	}
	return newList;
}

function setDiscofeedList(json){
	if (json){
		json_idp_list = getMatchIdPList(json, json_idp_list);
		json_idp_favoritelist = getMatchIdPList(json, json_idp_favoritelist);
		json_idp_hintlist = getMatchIdPList(json, json_idp_hintlist);
	}
	discofeed_flg = true;
	refresh_flg = false;
}

// DiscoFeed
function checkDiscofeed(discofeedurl){
	if (wayf_use_disco_feed || discofeedurl != ''){
		discofeed_flg = false;
		var urldomain = location.hostname;
		if(discofeedurl != ''){
			wayf_discofeed_url = discofeedurl;
		}
		if (wayf_discofeed_url != '/Shibboleth.sso/DiscoFeed'){
			urldomain = wayf_discofeed_url.split('/')[2];
		}
		if(location.hostname != urldomain && window.XDomainRequest){
			var xdr = new XDomainRequest();
			xdr.onload = function(){
				setDiscofeedList(eval("(" + xdr.responseText + ")"));
			}
			xdr.timeout = 10;
			xdr.open("get", wayf_discofeed_url, true);
			xdr.send( null );
		} else {
			(function($){
				$.ajax({
					type: 'get',
					url: wayf_discofeed_url,
					dataType: 'json',
					async: true,
					timeout: 10000,
					success: function(json) {
						setDiscofeedList(json);
					},
					error: function(json) {
						discofeed_flg = true;
						refresh_flg = false;
					}
				});
			}(jQuery));
		}
	}
}

function changeKind(){
	for(i = 0; i < IdPList.kindgroup.length; i++){
		if(IdPList.kindgroup[i].checked) {
			selkind = IdPList.kindgroup[i].value;
			break;
		}
	}
	IdPList.optionElm.click();
}

function changeKind_sel(){
	var selid = IdPList.kindgroup.selectedIndex;
	selkind = IdPList.kindgroup[selid].value;
	IdPList.optionElm.click();
}

function changeLocation(){
	for(i = 0; i < IdPList.locationgroup.length; i++){
		if(IdPList.locationgroup[i].checked) {
			sellocation = IdPList.locationgroup[i].value;
			break;
		}
	}
	IdPList.optionElm.click();
}

function changeLocation_sel(){
	var selid = IdPList.locationgroup.selectedIndex;
	sellocation = IdPList.locationgroup[selid].value;
	IdPList.optionElm.click();
}
