<h1></h1>

<h2></h2>

<div role="region" aria-labelledby="HeadersRow" tabindex="0" class="colheaders">
    <table>

        <thead>
        <tr>
            <th>Author</th>
            <th>Title</th>
            <th>Year</th>
            <th>ISBN-13</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Miguel De Cervantes</td>
            <td>The Ingenious Gentleman Don Quixote of La Mancha</td>
            <td>1605</td>
            <td>9783125798502</td>
        </tr>
        <tr>
            <td>Gabrielle-Suzanne Barbot de Villeneuve</td>
            <td>La Belle et la Bête</td>
            <td>1740</td>
            <td>9781910880067</td>
        </tr>
        <tr>
            <td>Sir Isaac Newton</td>
            <td>The Method of Fluxions and Infinite Series: With Its Application to the Geometry of Curve-lines</td>
            <td>1763</td>
            <td>9781330454862</td>
        </tr>
        <tr>
            <td>Mary Shelley</td>
            <td>Frankenstein; or, The Modern Prometheus</td>
            <td>1818</td>
            <td>9781530278442</td>
        </tr>
        <tr>
            <td>Herman Melville</td>
            <td>Moby-Dick; or, The Whale</td>
            <td>1851</td>
            <td>9781530697908</td>
        </tr>
        <tr>
            <td>Emma Dorothy Eliza Nevitte Southworth</td>
            <td>The Hidden Hand</td>
            <td>1888</td>
            <td>9780813512969</td>
        </tr>
        <tr>
            <td>F. Scott Fitzgerald</td>
            <td>The Great Gatsby</td>
            <td>1925</td>
            <td>9780743273565</td>
        </tr>
        <tr>
            <td>George Orwell</td>
            <td>Nineteen Eighty-Four</td>
            <td>1948</td>
            <td>9780451524935</td>
        </tr>
        <tr>
            <td>Nnedi Okorafor</td>
            <td>Who Fears Death</td>
            <td>2010</td>
            <td>9780756406691</td>
        </tr>
        </tbody>
    </table>
</div>





<style>
    body {
        font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, Roboto,
        Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        line-height: 1.4;
        color: #333;
        background-color: #fff;
        padding: 0 5vw;
    }

    /* Standard Tables */

    table {
        margin: 1em 0;
        border-collapse: collapse;
        border: 0.1em solid #d6d6d6;
    }

    caption {
        text-align: left;
        font-style: italic;
        padding: 0.25em 0.5em 0.5em 0.5em;
    }

    th,
    td {
        padding: 0.25em 0.5em 0.25em 1em;
        vertical-align: text-top;
        text-align: left;
        text-indent: -0.5em;
    }

    th {
        vertical-align: bottom;
        background-color: #666;
        color: #fff;
    }

    tr:nth-child(even) th[scope=row] {
        background-color: #f2f2f2;
    }

    tr:nth-child(odd) th[scope=row] {
        background-color: #fff;
    }

    tr:nth-child(even) {
        background-color: rgba(0, 0, 0, 0.05);
    }

    tr:nth-child(odd) {
        background-color: rgba(255, 255, 255, 0.05);
    }

    .colheaders td:nth-of-type(2) {
        font-style: italic;
    }

    .colheaders th:nth-of-type(3),
    .colheaders td:nth-of-type(3) {
        text-align: right;
    }

    .rowheaders td:nth-of-type(1) {
        font-style: italic;
    }

    .rowheaders th:nth-of-type(3),
    .rowheaders td:nth-of-type(2) {
        text-align: right;
    }

    /* Fixed Headers */

    th {
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    th[scope=row] {
        position: -webkit-sticky;
        position: sticky;
        left: 0;
        z-index: 1;
    }

    th[scope=row] {
        vertical-align: top;
        color: inherit;
        background-color: inherit;
        background: linear-gradient(90deg, transparent 0%, transparent calc(100% - .05em), #d6d6d6 calc(100% - .05em), #d6d6d6 100%);
    }

    th:not([scope=row]):first-child {
        left: 0;
        z-index: 3;
        background: linear-gradient(90deg, #666 0%, #666 calc(100% - .05em), #ccc calc(100% - .05em), #ccc 100%);
    }

    /* Scrolling wrapper */

    div[tabindex="0"][aria-labelledby][role="region"] {
        overflow: auto;
    }

    div[tabindex="0"][aria-labelledby][role="region"]:focus {
        box-shadow: 0 0 .5em rgba(0,0,0,.5);
        outline: .1em solid rgba(0,0,0,.1);
    }

    div[tabindex="0"][aria-labelledby][role="region"] table {
        margin: 0;
    }

    div[tabindex="0"][aria-labelledby][role="region"].rowheaders {
        background:
                linear-gradient(to right, transparent 30%, rgba(255,255,255,0)),
                linear-gradient(to right, rgba(255,255,255,0), white 70%) 0 100%,
                radial-gradient(farthest-side at 0% 50%, rgba(0,0,0,0.2), rgba(0,0,0,0)),
                radial-gradient(farthest-side at 100% 50%, rgba(0,0,0,0.2), rgba(0,0,0,0)) 0 100%;
        background-repeat: no-repeat;
        background-color: #fff;
        background-size: 4em 100%, 4em 100%, 1.4em 100%, 1.4em 100%;
        background-position: 0 0, 100%, 0 0, 100%;
        background-attachment: local, local, scroll, scroll;
    }

    div[tabindex="0"][aria-labelledby][role="region"].colheaders {
        background:
                linear-gradient(white 30%, rgba(255,255,255,0)),
                linear-gradient(rgba(255,255,255,0), white 70%) 0 100%,
                radial-gradient(farthest-side at 50% 0, rgba(0,0,0,.2), rgba(0,0,0,0)),
                radial-gradient(farthest-side at 50% 100%, rgba(0,0,0,.2), rgba(0,0,0,0)) 0 100%;
        background-repeat: no-repeat;
        background-color: #fff;
        background-size: 100% 4em, 100% 4em, 100% 1.4em, 100% 1.4em;
        background-attachment: local, local, scroll, scroll;
    }

    /* Strictly for making the scrolling happen. */

    th[scope=row] {
        min-width: 40vw;
    }

    @media all and (min-width: 30em) {
        th[scope=row] {
            min-width: 20em;
        }
    }

    th[scope=row] + td {
        min-width: 24em;
    }

    div[tabindex="0"][aria-labelledby][role="region"]:nth-child(3) {
        max-height: 18em;
    }

    div[tabindex="0"][aria-labelledby][role="region"]:nth-child(7) {
        max-height: 15em;
        margin: 0 1em;
    }
</style>