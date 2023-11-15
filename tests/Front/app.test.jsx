import * as React from "react";
import renderer from 'react-test-renderer';
import { act } from 'react-dom/test-utils';
import App from '../../resources/js/components/App';

it("renders the page correctly", async () => {
    const tree = renderer.create(<App />).toJSON();
    await expect(tree).toMatchSnapshot();
});

test("display the title correctly", () => {
    const tree = renderer.create(<App />)
    const root = tree.root;
    const titleElement = root.findByType('h1');
    expect(titleElement.children).toStrictEqual(["30 first entries"]);
});

test('should display the entry list items correctly', () => {
    let tree
    act(() => {
        tree = renderer.create(<App />)
    });
    global.fetch = jest.fn().mockResolvedValue({
        status: 200,
        entries: [],
    });
    const root = tree.root;

    const ulElement = root.findByType('ul');
    expect(ulElement).toBeDefined();
}); 