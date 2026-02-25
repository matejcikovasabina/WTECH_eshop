# Webová aplikácia - eshop

## Aplikácia musí realizovať tieto prípady použitia

### Klientská časť

- zobrazenie prehľadu všetkých produktov z vybratej kategórie používateľom
- základné filtrovanie (minimálne podľa 3 atribútov, napr. rozsah cena od-do, značka, farba)
- stránkovanie
- preusporiadanie produktov (minimálne podľa ceny vzostupne/zostupne)
- zobrazenie konkrétneho produktu - detail produktu
- pridanie produktu do košíka (ľubovolné množstvo, pozn. ak množstvo pre zvolenú doménu produktov nemá zmysel, nemusí byť implementované, napr. predaj áut)
- plnotextové vyhľadávanie nad katalógom produktov
- zobrazenie nákupného košíka
- zmena množstva pre daný produkt
- odobratie produktu
- výber dopravy
- výber platby
- zadanie dodacích údajov
- dokončenie objednávky
- umožnenie nákupu bez prihlásenia, uvažujúc možné dodatočné prihlásenie (pozor, treba sa zamyslieť, aby bol tento prípad použitia implementovaý správne)
- prenositeľnosť nákupného košíka v prípade prihláseného používateľa
- registrácia používateľa/zákazníka
- prihlásenie používateľa/zákazníka
- odhlásenie zákazníka

### Administrátorská časť

- prihlásenie administrátora do administrátorského rozhrania eshopu
- odhlásenie administrátora z administrátorského rozhrania
- vytvorenie nového produktu administrátorom cez administrátorské rozhranie
- produkt musí obsahovať minimálne názov, opis, aspoň 2 fotografie
- upravenie/vymazanie existujúceho produktu administrátorom cez administrátorské rozhranie
