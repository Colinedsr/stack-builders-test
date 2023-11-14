import { useEffect, useState } from "react"

export default function App() {
    const [entries, setEntries] = useState()
    const initialize = async () => {
        const data = await (await fetch('/scraper')).json();
        if (data.status === 200) {
            setEntries(data.entries);
        } else {
            console.error('error');
        }
    }

    useEffect(() => {
        initialize()
    }, [])

    return (
        entries && entries.map((e, index) =>
            <ul key={index}><div>{e.rank}- {e.title}</div><div>{e.points}, {e.comments}</div></ul>
        )
    );
}