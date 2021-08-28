class Ares {

    init()
    {
        console.log("Initializing Ares Front End");
        this.updateNavigation();
    }

    async talk(url = '', data = {}, reqMethod = 'GET')
    {
        const response = await fetch(url, {
            method: reqMethod,
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: {
              'Content-Type': 'application/json'
            },
            redirect: 'follow',
            referrerPolicy: 'no-referrer',
            body: JSON.stringify(data)
          });
          return response;
        //   return response.json();
    }

    updateNavigation()
    {
        console.log("Updating Navigation");
        
        this.talk('/api/getNavigation', {}, 'POST')
        .then(data => {
            console.log("Got Response");
            console.log("Response:", data)
        })
    }
}

let ares = new Ares();
ares.init();