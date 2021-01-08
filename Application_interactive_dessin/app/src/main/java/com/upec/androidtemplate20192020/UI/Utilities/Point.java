package com.upec.androidtemplate20192020.UI.Utilities;

public class Point {
        float x;
        float y;
        int color;
        int thickness;

        public Point(float x, float y, int color, int thickness) {
            this.x = x;
            this.y = y;
            this.color = color;
            this.thickness = thickness;
        }

        @Override
        public String toString()
        {
            return "x : " + x + " y : " + y + " color : " + color + " thickness : " + thickness;
        }

    public float getX() {
        return x;
    }

    public float getY() {
        return y;
    }

    public int getColor() {
        return color;
    }

    public int getThickness() {
        return thickness;
    }
}


