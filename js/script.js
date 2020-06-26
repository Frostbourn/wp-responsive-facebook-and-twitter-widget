const container = document.querySelector('.instagram-widget');
// container.style.maxWidth = widgetSetting.width;

const widgetSetting = {
    id: container.dataset.user,
    color: container.dataset.color,
    showHeader: container.dataset.header,
    // width: container.dataset.width
};
 
let header = document.createElement('div');
let statsPanel = document.createElement('div');
let gallery = document.createElement('div');
let footer = document.createElement('div');

const nFormat = (num) => {
    if (num >= 1000000) {
        return (num / 1000000).toFixed(1).replace(/\.0$/, '') + 'm';
    }
    if (num >= 1000) {
        return (num / 1000).toFixed(1).replace(/\.0$/, '') + 'k';
    }
    return num;
};

(async () => {
    try {
        const url = await axios.get(`https://www.instagram.com/${widgetSetting.id}`)
            .then(fetchData => {
                let instagramRegExp = new RegExp(/<script type="text\/javascript">window\._sharedData = (.*);<\/script>/);
                let values = fetchData.data.match(instagramRegExp)[1];
                let parsedResponse = JSON.parse(values);
                let userData = parsedResponse.entry_data.ProfilePage[0].graphql.user;
                return userData;
            })
            .then(userData => {
                if ((widgetSetting.showHeader) == 'yes') {
                    header.classList.add('widget-header');
                    header.innerHTML = `
                            <img src="${userData.profile_pic_url}" class="widget-header__avatar">
                            <span class="widget-header__username">${userData.full_name}</span>
                            <a href="https://instagram.com/${widgetSetting.id}" class="widget-button" target="_blank" style="${widgetSetting.color}">Follow</a>
                            `;
                    container.appendChild(header);

                    statsPanel.classList.add("widget-panel");
                    statsPanel.innerHTML =
                        `<p class=widget-panel__stats>${nFormat(userData.edge_owner_to_timeline_media.count)}<br /> <span>posts</span>
                         <p class=widget-panel__stats>${nFormat(userData.edge_followed_by.count)}<br /> <span>followers</span>
                         <p class=widget-panel__stats>${nFormat(userData.edge_follow.count)}<br /> <span>following</span>`;
                    container.appendChild(statsPanel);
                } else if ((widgetSetting.showHeader) == 'no') {
                    container.style.border = 'none';
                    container.style.boxShadow = 'none';
                }

                gallery.classList.add('widget-gallery');
                container.appendChild(gallery);

                let edges = userData.edge_owner_to_timeline_media.edges.splice(0, 12);
                let photos = edges.map(({
                    node
                }) => {
                    return {
                        url: `https://www.instagram.com/p/${node.shortcode}/`,
                        thumbnailUrl: node.thumbnail_src,
                        displayUrl: node.display_url,
                        caption: node.edge_media_to_caption.edges[0].node.text,
                        likesCount: node.edge_liked_by.count
                    }
                });
                return photos;
            })
            .then(photos => {
                photos.forEach(({
                    url,
                    thumbnailUrl,
                    caption,
                    likesCount
                }) => {
                    let photo = document.createElement('p');
                    photo.innerHTML = `
                    <a href="${url}" target="_blank" rel="noopener noreferrer" class="widget-gallery__link">
                        <img src="${thumbnailUrl}" alt="${caption}" class="widget-gallery__image">
                        <div class="widget-gallery__caption">
                            <p>${caption}</br>
                                <span>&#x2764;</span> ${nFormat(likesCount)}
                            </p>
                        </div>
                    </a>
                    `
                    gallery.appendChild(photo);
                });

            });
    } catch (e) {
        throw ('Unable to retrieve photos. Reason: ' + e);
    }
})();