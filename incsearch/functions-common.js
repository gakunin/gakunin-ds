function getMatchIdPList(json, list){
	var newList = new Array();
	var index = 0;
	var matchFlg = false;
	
	for (var i in list) {
		for (var j in json) {
			if (list[i].entityid == json[j].entityID) {
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
	if (!json) return;
	json_idp_list = getMatchIdPList(json, json_idp_list);
	json_idp_favoritelist = getMatchIdPList(json, json_idp_favoritelist);
	json_idp_hintlist = getMatchIdPList(json, json_idp_hintlist);
	discofeed_flg = true;
	refresh_flg = false;
}

// DiscoFeed
function checkDiscofeed(){
	if (typeof(wayf_use_disco_feed) == "undefined" || wayf_use_disco_feed){
		if (typeof(wayf_discofeed_url) != "undefined" && wayf_discofeed_url != ''){
			var urldomain = wayf_discofeed_url.split('/')[2];
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
}

function changeKind(){
	for(i = 0; i < IdPList.kindgroup.length; i++){
		if(IdPList.kindgroup[i].checked) {
			selkind = IdPList.kindgroup[i].value;
			break;
		}
	}
}
