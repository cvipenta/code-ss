
/*
var contentArray = new Array();
contentArray[0] = '<a href="http://www.startsanatate.ro" title="-">0 news title text news title text news title text</a><br style="margin-bottom: 5px;">';
contentArray[1] = '<a href="http://www.startsanatate.ro" title="-">1 news title text news title text news title text</a><br style="margin-bottom: 5px;">';
contentArray[2] = '<a href="http://www.startsanatate.ro" title="-">2 news title text news title text news title text</a><br style="margin-bottom: 5px;">';
contentArray[3] = '<a href="http://www.startsanatate.ro" title="-">3 news title text news title text news title text</a><br style="margin-bottom: 5px;">';
contentArray[4] = '<a href="http://www.startsanatate.ro" title="-">4 news title text news title text news title text</a><br style="margin-bottom: 5px;">';
contentArray[5] = '<a href="http://www.startsanatate.ro" title="-">5 news title text news title text news title text</a><br style="margin-bottom: 5px;">';
contentArray[6] = '<a href="http://www.startsanatate.ro" title="-">6 news title text news title text news title text</a><br style="margin-bottom: 5px;">';
contentArray[7] = '<a href="http://www.startsanatate.ro" title="-">7 news title text news title text news title text</a><br style="margin-bottom: 5px;">';
contentArray[8] = '<a href="http://www.startsanatate.ro" title="-">8 news title text news title text news title text</a><br style="margin-bottom: 5px;">';
contentArray[9] = '<a href="http://www.startsanatate.ro" title="-">9 news title text news title text news title text</a><br style="margin-bottom: 5px;">';
*/

var nIndex = 0;
var timerID = null;

function rotateNews()
{
	
	var len = contentArray.length;
	var newsDisplayed = 5;
	var newsToShow = '';
	
	if(nIndex >= len)
		nIndex = 0;
	
	for(var newsDisplayedCounter = 0; newsDisplayedCounter < newsDisplayed; newsDisplayedCounter++)
	{	
		newsToShow += contentArray[nIndex];
		nIndex++;
	}
	
	
	document.getElementById('stories').innerHTML = newsToShow;
	
	timerID = setTimeout('rotateNews()', 2000);
}

function pauseNews() {
	if (timerID != null) {
		clearTimeout(timerID);
		timerID = null;
	}
}

function playNews() {
	if (timerID == null) {
		timerID = setTimeout('rotateNews()', 1000);
	}
}

/*
///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////
*/