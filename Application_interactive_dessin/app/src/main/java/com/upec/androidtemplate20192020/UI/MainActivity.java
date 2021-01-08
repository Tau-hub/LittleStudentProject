package com.upec.androidtemplate20192020.UI;

import android.app.Dialog;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;


import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.view.menu.MenuBuilder;
import androidx.appcompat.widget.AppCompatDrawableManager;
import androidx.appcompat.widget.Toolbar;

import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;

import android.widget.Button;
import android.widget.EditText;
import android.widget.SeekBar;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.upec.androidtemplate20192020.Client.Client;
import com.upec.androidtemplate20192020.R;
import com.upec.androidtemplate20192020.Server.ClientEvent;
import com.upec.androidtemplate20192020.Server.ServerCore;
import com.upec.androidtemplate20192020.UI.Utilities.Dessin;
import com.upec.androidtemplate20192020.UI.Utilities.Point;

import java.util.ArrayList;

import yuku.ambilwarna.AmbilWarnaDialog;

public class MainActivity extends AppCompatActivity {
    public static ArrayList<String> userList = new ArrayList<>();
    public static ArrayList<Point> points = new ArrayList<>();
    int color = Dessin.couleur;
    int thickness = Dessin.epaisseur;
    int rubber_color;
    String pseudo = "Mypseudo";
    String ip = "";

    boolean connectionEnable = true;
    boolean floatingEditorVisibility = true;
    private ServerCore ss = null;
    public static Client c = null;


    Dialog sizeDialog;
    Dialog ipDialog;
    Dialog pseudoDialog;
    Dialog infoDialog;
    EditText ip_edit_text;
    EditText pseudo_edit_text;
    Button ip_connection_button;
    Button pseudo_submit_button;
    SeekBar seekBar;



    @Override
    protected void onCreate(Bundle savedInstanceState) {


        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        ColorDrawable colorDrawable = (ColorDrawable) getWindow().getDecorView().getBackground();

        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        floatingActionButton();

        Dessin dessin = findViewById(R.id.dessin);
        dessin.setInfo(color, thickness);

        int colorBackground = colorDrawable.getColor();
        sizeDialog = new Dialog(this);
        sizeDialog.setContentView(R.layout.thickness_popup);
        ipDialog = new Dialog(this);
        ipDialog.setContentView(R.layout.join_popup);
        ip_edit_text = ipDialog.findViewById(R.id.ip);
        ip_connection_button = ipDialog.findViewById(R.id.connect);

        pseudoDialog = new Dialog(this);
        pseudoDialog.setContentView(R.layout.pseudo_popup);
        pseudo_edit_text = pseudoDialog.findViewById(R.id.pseudo);
        pseudo_submit_button = pseudoDialog.findViewById(R.id.pseudo_submit);
        seekBar = sizeDialog.findViewById(R.id.size_seekbar);
        TextView size_text = sizeDialog.findViewById(R.id.size_text);
        size_text.setText(Dessin.epaisseur + "");
        seekBar.setProgress(Dessin.epaisseur);

        infoDialog = new Dialog(this);

        rubber_color = colorBackground;
        thickness = Dessin.epaisseur;





    }


    private void floatingActionButton() {
        final FloatingActionButton color_icon = findViewById(R.id.color);
        final FloatingActionButton size_icon = findViewById(R.id.size);
        final FloatingActionButton edit_icon = findViewById(R.id.edit);
        final FloatingActionButton rubber_icon = findViewById(R.id.rubber);

        edit_icon.setImageDrawable(
                AppCompatDrawableManager.get().getDrawable(this, R.drawable.ic_mode_edit_24dp));

        edit_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (floatingEditorVisibility) {
                    edit_icon.hide();
                    rubber_icon.show();
                    color_icon.show();
                    size_icon.show();
                } else {
                    floatingEditorVisibility = true;
                    rubber_icon.hide();
                    color_icon.hide();
                    size_icon.hide();
                    Dessin dessin = findViewById(R.id.dessin);
                    dessin.setColor(color);
                }


            }
        });



        color_icon.setImageDrawable(
                AppCompatDrawableManager.get().getDrawable(this, R.drawable.ic_color_lens_24dp));

        color_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                floatingEditorVisibility = true;
                edit_icon.show();
                rubber_icon.hide();
                color_icon.hide();
                size_icon.hide();
                openColorPicker();
            }
        });


        size_icon.setImageDrawable(
                AppCompatDrawableManager.get().getDrawable(this, R.drawable.ic_text_fields_24dp));


        size_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                sizeDialog.show();
                seekBar.setOnSeekBarChangeListener(new SeekBar.OnSeekBarChangeListener() {
                    @Override
                    public void onProgressChanged(SeekBar seekBar, int i, boolean b) {
                        TextView size_text = sizeDialog.findViewById(R.id.size_text);
                        size_text.setText(i + "");
                        Dessin.epaisseur = i;
                   }

                    @Override
                    public void onStartTrackingTouch(SeekBar seekBar) {

                    }

                    @Override
                    public void onStopTrackingTouch(SeekBar seekBar) {

                    }
                });
                if(floatingEditorVisibility)
                {
                    color_icon.hide();
                    size_icon.hide();
                    rubber_icon.hide();
                    edit_icon.show();
                }
                else {
                    color_icon.hide();
                    size_icon.hide();
                    rubber_icon.show();
                    edit_icon.hide();
                }
            }

        });

        rubber_icon.setImageDrawable(
                AppCompatDrawableManager.get().getDrawable(this, R.drawable.ic_rubber));

        rubber_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (floatingEditorVisibility) {
                    edit_icon.hide();
                    color_icon.hide();
                    size_icon.hide();
                    floatingEditorVisibility = false;
                    Dessin dessin = findViewById(R.id.dessin);
                    dessin.setColor(rubber_color);
                } else {
                    rubber_icon.hide();
                    edit_icon.show();
                    color_icon.show();
                    size_icon.show();

                }

            }
        });

    }

    public void notifyNewPoint() {
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                Dessin dessin = findViewById(R.id.dessin);
                dessin.invalidate();
            }
        });
    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu_main, menu);

        if (menu instanceof MenuBuilder) {
            MenuBuilder m = (MenuBuilder) menu;
            m.setOptionalIconsVisible(true);
        }
        return super.onCreateOptionsMenu(menu);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        Dessin dessin = findViewById(R.id.dessin);

        switch (id) {
            case R.id.join:
                Dessin.width = dessin.getWidth();
                Dessin.height = dessin.getHeight();
                showPopupIp();
                break;

            case R.id.host:
                ss = new ServerCore();
                ss.start();
                c = new Client(pseudo, this);
                c.start();
                connectionEnable = false;
                Dessin.width = dessin.getWidth();
                Dessin.height = dessin.getHeight();
                break;

            case R.id.item_pseudo:
                showPopupPseudo();
                break;

            case R.id.disconnect:
                if (ss != null)
                {
                    ss.close();
                    ss = null;
                }

             if(c != null)
                    c.close();
                break;

            case R.id.refresh :
                Context context = getApplicationContext();
                Toast toast;
                if(ss != null)
                {
                    dessin = findViewById(R.id.dessin);
                    dessin.reset();
                    new Thread(new Runnable() {
                        @Override
                        public void run() {
                            ClientEvent.resetAllSheets();
                        }
                    }).start();
                    toast =  Toast.makeText(context, "Blank sheet", Toast.LENGTH_SHORT);
                }
                else if(c == null)
                {
                    dessin = findViewById(R.id.dessin);
                    dessin.reset();
                    toast =  Toast.makeText(context, "Blank sheet", Toast.LENGTH_SHORT);
                }
                else
                {
                   toast =  Toast.makeText(context, "Only the master of the server can reset the sheet.", Toast.LENGTH_SHORT);
                }
                toast.show();

                break;

            case R.id.ic_info:
                startActivity(new Intent(this, UserInfoActivity.class));
                break;
        }


        return super.onOptionsItemSelected(item);
    }

    @Override
    public boolean onPrepareOptionsMenu(Menu menu) {
        menu.findItem(R.id.join).setEnabled(connectionEnable);
        menu.findItem(R.id.host).setEnabled(connectionEnable);
        menu.findItem(R.id.disconnect).setVisible(!connectionEnable);
        return super.onPrepareOptionsMenu(menu);
    }


    public void showPopupIp() {

        ip_connection_button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                ip = ip_edit_text.getText().toString();
                c = new Client(ip, pseudo, MainActivity.this);
                c.start();
            }
        });
        ipDialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
        ipDialog.show();
    }

    public void showPopupPseudo() {
        pseudo_submit_button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                pseudo = pseudo_edit_text.getText().toString();
                if (c != null) {
                    new Thread(new Runnable() {
                        @Override
                        public void run() {
                            c.changeName(pseudo);
                            c.register();
                        }
                    }).start();
                }
                pseudoDialog.dismiss();
            }
        });
        pseudoDialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
        pseudoDialog.show();
    }

    private void openColorPicker() {
        AmbilWarnaDialog colorPicker = new AmbilWarnaDialog(this, color, new AmbilWarnaDialog.OnAmbilWarnaListener() {
            @Override
            public void onCancel(AmbilWarnaDialog dialog) {

            }

            @Override
            public void onOk(AmbilWarnaDialog dialog, int color) {
                MainActivity.this.color = color;

                Dessin dessin = findViewById(R.id.dessin);
                dessin.setColor(color);

            }
        });
        colorPicker.show();
    }

    public void clientConnected() {
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                Context context = getApplicationContext();
                Toast toast;
                connectionEnable = false;
                Dessin dessin = findViewById(R.id.dessin);
                dessin.reset();
                ipDialog.dismiss();
                toast = Toast.makeText(context, "Connected to the server!", Toast.LENGTH_SHORT);
                toast.show();
            }
        });


    }

    public void clientNotConnected() {
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                Context context = getApplicationContext();
                Toast toast;
                if (connectionEnable) {
                    toast = Toast.makeText(context, "Connection didn't work, try another IP!", Toast.LENGTH_SHORT);
                    toast.show();
                }
                else {
                    toast = Toast.makeText(context, "Disconnected", Toast.LENGTH_SHORT);
                    connectionEnable = true;
                }
                c = null;
                toast.show();
            }
        });

    }
}
