function defineTabID(sito)
    {
      console.log('defineTabID');
      
    var iPageTabID = sessionStorage.getItem("tabID");
      // if it is the first time that this page is loaded
    if (iPageTabID == null)
        {
        var iLocalTabID = localStorage.getItem("tabID");
          // if tabID is not yet defined in localStorage it is initialized to 1
          // else tabId counter is increment by 1
        var iPageTabID = (iLocalTabID == null) ? 1 : Number(iLocalTabID) + 1;
          // new computed value are saved in localStorage and in sessionStorage
        localStorage.setItem("tabID",iPageTabID);
        sessionStorage.setItem("tabID",iPageTabID);
        if(!sessionStorage.tab) {    
          //  document.cookie = 'tab_id=' + sessionStorage.getItem("tabID");
            $.cookie('tab_id',  sessionStorage.getItem("tabID"));
            console.log('tab_id:'+sessionStorage.getItem("tabID"));
        }      

      
        } 
        sito = sito.toLowerCase();

        if(sito=='user' || sito=='work') 
          {
            console.log('sito:'+sito);
            $.cookie($.cookie('tab_id'), sito);
          }
         

          if(!sito && !$.cookie($.cookie('tab_id'))) 
            {
              var num = $.cookie('tab_id')-1; 
              var oldsito = ($.cookie(num.toString()))?$.cookie(num.toString()):'user';
              $.cookie($.cookie('tab_id'), oldsito);
            }

          console.log($.cookie());
    }
 

if(!sessionStorage.tab) {
//location.reload();
}
//set tab_id cookie before leaving page
window.addEventListener('beforeunload', function() {
  console.log('addEventListener beforeunload');
    document.cookie = 'tab_id=' + sessionStorage.getItem("tabID");
  //  location.reload();
});


function verificaTabId(sito, tab_sito)
{
 sito = sito.toLowerCase();
 
  console.log('verificaTabId'+sito+" tab_sito"+tab_sito);
  if(sito && sito!=tab_sito)
  {
    location.reload();
  }
  if(!tab_sito)
  {
    location.reload();
  }
}