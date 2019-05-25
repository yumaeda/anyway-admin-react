/**
 * WineTableRow component
 *
 * @author Yukitaka Maeda [yumaeda@gmail.com]
 */
import * as React from 'react'
import { IWine } from '../../../interfaces/IWine'
import { defaultWine } from '../../../states'

/**
 * Interface for props
 */
interface IProps {
    code: string
    qty: string
}

/**
 * WineTableRow component
 */
const WineTableRow: React.FC<IProps> = props => {
    const { code, qty } = props
    const [wine, setWine] = React.useState<IWine>(defaultWine)

    React.useEffect(() => {
        const baseUri = '//anyway-grapes.jp/laravel5.3/public/api/v1/wines'
        fetch(`${baseUri}/${code}`)
            .then(response => response.json())
            .then(response => {
                if (response.wines.length > 0) {
                    setWine(response.wines[0])
                }
            })
            .catch(error => {
                alert(error.stack)
            })
    }, [])

    return (
        <tr key={wine.barcode_number}>
            <td>
                <input
                    type="text"
                    value={wine.barcode_number}
                    className="wine_code"
                />
            </td>
            <td>{wine.vintage}</td>
            <td>{wine.combined_name}</td>
            <td>{` by ${wine.producer}`}</td>
            <td>{` x ${qty}`}</td>
        </tr>
    )
}

export default React.memo(WineTableRow)
