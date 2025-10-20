class MapComponent {
    constructor(mapElementId, locations, iconColors) {
        this.mapElementId = mapElementId;
        this.locations = locations;
        this.iconColors = iconColors;
        this.map = null;
        this.markers = [];
    }

    initComponent() {
        this.map = new google.maps.Map(document.getElementById(this.mapElementId), {
            zoom: 11,
            center: {
                lat: this.locations[0].lat,
                lng: this.locations[0].long,
            },
        });

        this.locations.forEach((location, index) => {
            const contentString = `
                <div>
                    <h6>${location.title}</h6>
                    <p>${location.description}</p>
                </div>
            `;

            const infoWindow = new google.maps.InfoWindow({
                content: contentString,
            });

            const marker = new google.maps.Marker({
                position: {
                    lat: location.lat,
                    lng: location.long,
                },
                map: this.map,
                title: location.name,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    fillColor: this.iconColors[index],
                    fillOpacity: 1,
                    scale: 6,
                    strokeColor: 'white',
                    strokeWeight: 1,
                },
            });

            marker.addListener('click', () => {
                infoWindow.open(this.map, marker);
            });

            this.markers.push(marker);
        });

        this.addListClickHandlers();
    }

    addListClickHandlers() {
        document.querySelectorAll('.location-item').forEach(item => {
            item.addEventListener('click', () => {
                const index = parseInt(item.getAttribute('data-table'), 10);
                const location = this.locations[index];

                this.map.setCenter({ lat: location.lat, lng: location.long });

                const marker = this.markers[index];
                marker.setAnimation(google.maps.Animation.BOUNCE);
                setTimeout(() => marker.setAnimation(null), 1400);
            });
        });
    }

    static getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }

        return color;
    }

    static generateRandomColors(elementSelector) {
        const iconColors = [];

        document.querySelectorAll(elementSelector).forEach(element => {
            const randomColor = MapComponent.getRandomColor();
            iconColors.push(randomColor);
            element.querySelector('.fa-map-marker').style.color = randomColor;
        });

        return iconColors;
    }
}

window.initMap = function () {
    const iconColors = MapComponent.generateRandomColors('.random-color-icon');
    const mapComponent = new MapComponent('map', window.locations, iconColors);
    mapComponent.initComponent();
};
