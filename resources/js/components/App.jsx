import * as React from "react";
import { useEffect, useState } from "react"

export default function App() {
    const [entries, setEntries] = useState([])
    const [errorMessage, setErrorMessage] = useState('')
    const initialize = async () => {
        try {
            const data = await (await fetch('/scraper')).json();
            if (data.status === 200) {
                setEntries(data.entries);
            } else {
                setErrorMessage(data.error);
            }
        } catch (error) { console.log(error) }
    }

    useEffect(() => {
        initialize();
    }, [])

    return (
        <><h1>30 first entries</h1>
            {entries.length === 0 && errorMessage.length === 0 && <p>... scraping data</p>}
            {errorMessage && <p>{errorMessage}</p>}
            <ul>
                {entries && entries.map((e, index) => <li key={index}>
                    <div>{e.rank}- {e.title}</div>
                    <div>{e.points} points, {e.comments} comment(s)</div>
                </li>
                )}
            </ul>
        </>
    );
}