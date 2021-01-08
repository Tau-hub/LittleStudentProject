package com.upec.androidtemplate20192020.UI.Utilities;

import android.content.Context;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.util.AttributeSet;
import android.view.MotionEvent;
import android.view.View;

import androidx.annotation.Nullable;

import com.upec.androidtemplate20192020.UI.MainActivity;

import java.util.ArrayList;
import java.util.concurrent.CopyOnWriteArrayList;

import static com.upec.androidtemplate20192020.UI.MainActivity.points;

public class  Dessin extends View {
    public static int epaisseur = 20;
    public static int couleur = Color.BLACK;
    public  static int width;
    public  static int height;

    public Dessin(Context context, @Nullable AttributeSet attrs) {
        super(context, attrs);
    }


    @Override
    protected void onDraw(Canvas canvas) {
        super.onDraw(canvas);
        Paint paint = new Paint();
        CopyOnWriteArrayList<Point> points_copy = new CopyOnWriteArrayList<>(points);
        for(Point point : points_copy){
            paint.setColor(point.color);
            canvas.drawCircle(point.x, point.y, point.thickness, paint);
        }
    }


    @Override
    public boolean onTouchEvent(MotionEvent event) {
       final Point p = new Point(event.getX(), event.getY(), couleur, epaisseur);
        MainActivity.points.add(p);
        if(MainActivity.c != null) {
            new Thread(new Runnable() {
                @Override
                public void run() {
                    MainActivity.c.sendNewPoint(p);
                }
            }).start();
        }
        invalidate();
        return true;
    }


    public void setInfo(int couleur, int epaisseur){
        this.epaisseur = epaisseur;
        this.couleur = couleur;
    }

    public void setColor(int couleur) {
        this.couleur = couleur;
    }

    public void reset(){
        points = new ArrayList<>();
        invalidate();
    }
}
