package com.upec.androidtemplate20192020.UI;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Context;
import android.net.wifi.WifiInfo;
import android.net.wifi.WifiManager;
import android.os.Bundle;
import android.widget.ListView;
import android.widget.TextView;

import com.upec.androidtemplate20192020.R;

public class UserInfoActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user_info);
        Context context = this;
        WifiManager wifiMan = (WifiManager) context.getSystemService(Context.WIFI_SERVICE);
        WifiInfo wifiInf = wifiMan.getConnectionInfo();
        int ipAddress = wifiInf.getIpAddress();
        String ip = String.format("%d.%d.%d.%d", (ipAddress & 0xff),(ipAddress >> 8 & 0xff),(ipAddress >> 16 & 0xff),(ipAddress >> 24 & 0xff));
        TextView userIp = findViewById(R.id.ipinfo);
        userIp.setText(ip);
        ListView listView = findViewById(R.id.userview);
        UserListAdapter adapter = new UserListAdapter(this, MainActivity.userList);
        listView.setAdapter(adapter);
    }
}
