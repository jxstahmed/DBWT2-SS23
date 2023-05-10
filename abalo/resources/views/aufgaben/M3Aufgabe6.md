# Google Slides API
## 1.1	Was ist der Zweck der API?
Durch die API werden die Google Slied Präsentationen sowohl gelesen als auch geschrieben.

## 1.2	Welche REST Prinzipien werden umgesetzt?
###	Bedingung 1: Client-Server Architektur
Diese ist gegeben.\
Es werden mit responses von einem Server gearbeitet, an welchen ebenso requests gesendet werden.

###	Bedingung 2: Zustandslos
Diese ist gegeben.\
Es müssen zu immer alle Informationen mitgesendet werden. Dies können zum Beispiel die IDs der jeweiligen Präsentation sein.

###	Bedingung 3: Cache
Die jeweiligen Header der einzelnen Anfragen können angepasst werden. Bei der Recherche schien es so, als wäre es ebenso möglich die Cache-Control-Direktive des HTTP-Header anzupassen.

###	Bedingung 4: Einheitliche Schnittstelle
Diese ist gegeben.\
Der Service Endpoint bleibt immer gleich. [https://slides.googleapis.com]\
Alle URIs sind relativ zu diesem service endpoint und folgen einem bestimmten Schema.\

###	Bedingung 5: Mehrschichtige Systeme
Diese ist gegeben.\
Es wird für unterschiedliche Programmiersprachen beschriebe, wie es möglich ist die API in eine Applikation einzubauen.

###	Bedingung 6: Code on Demand
Diese ist nicht gegeben.

## 1.3	Auf welchem Level (nach dem Richardson Maturity Model) befindet sich die API?
Die API befindet sich auf dem Level 3.\
Das Level 2 ist gegeben durch unterschiedliche http Verben.\
Das Level 3 ist gegeben, aufgrund der Hinweis auf Verlinkung der Ressourcen:\
https://developers.google.com/slides/api/guides/performance?hl=en )

## 1.4	Wie ist eine Versionierung implementiert?
Die jeweilige Version wird immer im Pfad genannt:\
https://slides.googleapis.com/v1/...
#### Quelle: https://developers.google.com/slides/api/reference/rest

# Facebook Graph API
## 2.1	Was ist der Zweck der API?
Die API wird genutzt, damit Apps Daten in den Facebook Social Graph schreiben und lesen können.

## 2.2	Welche REST Prinzipien werden umgesetzt?
Alle der REST Prinzipien, da Facebook diese API selber als RESTful beschreibt.

## 2.3	Auf welchem Level (nach dem Richardson Maturity Model) befindet sich die API?
Das Level 3 ist gegeben, aufgrund der Hinweis auf Verlinkung der Ressourcen:\
https://developers.facebook.com/docs/graph-api/overview/ )

## 2.4	Wie ist eine Versionierung implementiert?
Die jeweilige Version wird immer im Pfad genannt:\
https://graph.facebook.com/v4.0/...
