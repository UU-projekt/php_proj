# Projektarbete - individuellt
> 🚨 Detta projekt förutsätter att det ligger direkt i http-mappen (dvs att url till index är http://localhost/index.php). Projektet *bör* fungera oavsett

## Testat på
- **OS -** Ubuntu 22.04.3 LTS (via WSL)
- **PHP -** PHP 8.1.2-1ubuntu2.17 (cli) (built: May  1 2024 10:10:07) (NTS)
- **webb-server -** Apache2 verision 2.4.52-1ubuntu4.9
- **webbläsare:** firefox v 126.0

## Möjliga problem
- återställningen av lösenord kan ibland stöka om din epost-levarantör inte litar på tjänsten jag använder (postmark). Detta bör vara ganska sällsynt då dkim är etablerat men man vet aldrig. Kan varmt rekomendera [postmarks videos](https://postmarkapp.com/guides/dmarc#video-summary) ang dkim
- textens storlek kanske kan vara lite för stor / liten på vissa skärmar. Detta eftersom jag suger på front end
- vissa variabelnamn kanske kan vara lite opassande. Blir så när jag skriver kod när jag borde sova