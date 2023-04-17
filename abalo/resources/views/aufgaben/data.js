var data = {
    'produkte': [
        { name: 'Ritterburg', preis: 59.99, kategorie: 1, anzahl: 3 },
        { name: 'Gartenschlau 10m', preis: 6.50, kategorie: 2, anzahl: 5 },
        { name: 'Robomaster' ,preis: 199.99, kategorie: 1, anzahl: 2 },
        { name: 'Pool 250x400', preis: 250, kategorie: 2, anzahl: 8 },
        { name: 'Rasenmähroboter', preis: 380.95, kategorie: 2, anzahl: 4 },
        { name: 'Prinzessinnenschloss', preis: 59.99, kategorie: 1, anzahl: 5 }
    ],
    'kategorien': [
        { id: 1, name: 'Spielzeug' },
        { id: 2, name: 'Garten' }
    ]
};

function getMaxPreis()
{
    let max = 0;
    let name;
    for (let i = 0; i< data['produkte'].length; i++)
    {
        if(data['produkte'][i]['preis'] > max)
        {
            max = data['produkte'][i]['preis'];
            name = data['produkte'][i]['name'];
        }
    }
    return name;
}
document.getElementById('maxPreis').innerText = getMaxPreis(data);

function getMinPreis()
{
    let min = 9999999;
    let name;
    for (let i = 0; i< data['produkte'].length; i++)
    {
        if(data['produkte'][i]['preis'] < min)
        {
            min = data['produkte'][i]['preis'];
            name = data['produkte'][i]['name'];
        }
    }
    return name;
}
document.getElementById('minPreis').innerText = getMinPreis(data);

function getSum()
{
    let sum = 0;
    for (let i = 0; i< data['produkte'].length; i++)
    {
        sum += data['produkte'][i]['preis'];
    }
    return sum;
}
document.getElementById('PreisSum').innerText = getSum(data).toFixed(3);

function getGesamt()
{
    let gesamt = 0;
    for (let i = 0; i< data['produkte'].length; i++)
    {
        gesamt += data['produkte'][i]['preis'] * data['produkte'][i]['anzahl'];
    }
    return gesamt;
}
document.getElementById('GesamtWert').innerText = getGesamt(data).toFixed(3);

function getAnzahl(data,category)
{
    let anzahl = 0;
    for (let i = 0; i< data['produkte'].length; i++)
    {
        if(data['produkte'][i]['kategorie'] === category)
        {
            anzahl += data['produkte'][i]['anzahl'];
        }
    }
    return anzahl;
}
document.getElementById('anzahl1').innerText = getAnzahl(data,1);
document.getElementById('anzahl2').innerText = getAnzahl(data,2);
