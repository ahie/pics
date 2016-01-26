var loading = false;

window.onscroll = function(ev) {
        if ((window.innerHeight + window.scrollY)
        >= document.body.offsetHeight) {
                getMorePics();
        }
};

function getMorePics() {
        if(!loading) {
                loading = true;
                var divs = document.getElementById('pic-container').children;
                var lastid = divs[divs.length - 1].id;
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'next16/' + lastid, true);
                xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                                appendPics(JSON.parse(xhr.response));
                        }
                        loading = false;
                };
                xhr.send();

                function appendPics(pics) {
                        pics.forEach(function(pic) {
                                var node = document.createElement('DIV');
                                node.id = pic.id;
                                node.style.float = "left";
                                node.style.margin = "1px";
                                node.innerHTML =
                                '<a href="//pics.cloudapp.net/' + pic.id + '">' +
                                '<img src="' + pic.url + pic.id + '.t">' +
                                '</a>';
                                document.getElementById('pic-container').appendChild(node);
                        });
                }
        }
}


