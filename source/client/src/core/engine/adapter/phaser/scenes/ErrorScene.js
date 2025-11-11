import BaseScene from "./BaseScene";

export default class ErrorScene extends BaseScene {

    constructor ()
    {
        super({ key: 'ErrorScene' });
    }

    preload ()
    {
        super.preload();
    }

    create (data)
    {
        super.create();

        let errorMsg = this.add.dom().createFromCache('error', 'error-wrapper');

        for (let item of errorMsg.node.getElementsByClassName('error-title')) {
            item.innerHTML = data.title || '';
        }

        for (let item of errorMsg.node.getElementsByClassName('error-message')) {
            item.innerHTML = data.message || '';
        }
    }

    update ()
    {
    }
}

