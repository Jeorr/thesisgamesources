import { v4 as uuidv4 } from "uuid";

export default class MathUtility {
    static generateRandomId() {
        return uuidv4();
    }
}