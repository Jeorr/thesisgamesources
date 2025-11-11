import UpdateCliDataEvent from "../../events/userevents/UpdateCliDataEvent";

export default class Scene {
    constructor() {
        this.original = null;
        this.units = {};
        this.objects = {};
    }

    getOriginal() {
        return this.original;
    }

    setOriginal(original) {
        this.original = original;
    }

    addUnit(unit) {
        this.units[unit.getId()] = unit;
    }

    getUnits() {
        return this.units;
    }

    getUnitById(id) {
        if (this.units[id]) {
            return this.units[id];
        }
        return null;
    }

    getUnitsByTag(tag) {
        let units = [];

        for (let unitId in this.units) {
            let unit = this.units[unitId];
            if (unit.hasTag(tag)) {
                units.push(unit);
            }
        }

        return units;
    }

    addObject(object) {
        this.objects[object.getInternalId()] = object;
    }

    getObjects() {
        return this.objects;
    }

    getObjectsByTag(tag) {
        let objects = [];

        for (let objInternalId in this.objects) {
            let obj = this.objects[objInternalId];
            if (obj.hasTag(tag)) {
                objects.push(obj);
            }
        }

        return objects;
    }

    removeObjectByInternalId(internalId) {
        delete this.objects[internalId];
    }
}
