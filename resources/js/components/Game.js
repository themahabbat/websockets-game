import React, { useState, useEffect, useRef } from 'react';
import ReactDOM from 'react-dom';

const Game = props => {


    const CANVAS_WIDTH = 500
    const CANVAS_HEIGHT = 500

    const PLAYER_WIDTH = 40
    const PLAYER_HEIGHT = 40

    const canvas = document.getElementById('game-canvas')

    canvas.width = CANVAS_WIDTH
    canvas.height = CANVAS_HEIGHT
    canvas.style.width = `${CANVAS_WIDTH}px`
    canvas.style.height = `${CANVAS_HEIGHT}px`

    const ctx = canvas.getContext('2d')







    const [users, setUsers] = useState({})


    const images = [
        ['player', './public/images/player.png']
    ]

    let loadedImages = {}

    const promiseArray = images.map(item => {
        var prom = new Promise((resolve, reject) => {
            var img = new Image()
            img.onload = function () {
                loadedImages[item[0]] = img
                resolve();
            }
            img.src = item[1]
        })
        return prom;
    })


    Promise.all(promiseArray)


    let conn = null
    let name = null

    const socket_send = (json) => {
        conn.send(JSON.stringify(json));
    }


    useEffect(() => {


        name = prompt('Ad')

        if (name) {
            conn = new WebSocket('ws://192.168.0.222:4444');

            conn.onopen = function (e) {
                console.log("Connection established!");

                socket_send({
                    event: 'name',
                    name
                })

            }

            conn.onmessage = (e) => {
                const data = JSON.parse(e.data)

                const { event } = data

                if (event === 'users') {
                    setUsers(data.users)
                }


            }
        }

    }, [1])


    useEffect(() => {


        document.addEventListener('keydown', (e) => {

            socket_send({
                event: 'movement',
                to: e.key
            })

        })


    }, [1])


    useEffect(() => {

        Promise.all(promiseArray).then(() => {
            ctx.beginPath()
            ctx.rect(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT)
            ctx.fillStyle = "lightyellow"
            ctx.fill()

            Object.keys(users).forEach(id => {
                const user = users[id]

                if (user['name']) {
                    ctx.fillStyle = 'red'
                    ctx.textAlign = 'center'
                    ctx.font = "20px Arial";
                    ctx.fillText(user['name'], user.x + PLAYER_WIDTH / 2, user.y + PLAYER_HEIGHT + 20);
                }


                ctx.drawImage(loadedImages['player'], user.x, user.y, PLAYER_WIDTH, PLAYER_HEIGHT)

            })
        })


    }, [users])



    return (
        <></>
    )

}

if (document.getElementById('react-game')) {
    ReactDOM.render(<Game />, document.getElementById('react-game'));
}


export default Game
