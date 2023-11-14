export default function App() {
    const initialize = () => {
        fetch('', {
            method: 'get',
            headers: { 'Content-Type': 'application/json' },
        })
            .then((response) => response.json().then(data => {
                setDreamers(data)
            }))
    }
    return (
        <p>Hello</p>
    )
}