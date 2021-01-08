package com.upec.androidtemplate20192020.UI;

import android.content.Context;
import android.net.wifi.WifiInfo;
import android.net.wifi.WifiManager;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import com.upec.androidtemplate20192020.R;

import java.util.ArrayList;

public class UserListAdapter extends BaseAdapter {
    private Context context;
    private ArrayList<String> userNameList;
    private LayoutInflater inflater;

    public UserListAdapter(Context context, ArrayList<String> userNameList) {
        this.context = context;
        this.userNameList = userNameList;
        inflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
    }

    @Override
    public int getCount() {
        return userNameList.size();
    }

    @Override
    public Object getItem(int position) {
        return userNameList.get(position);
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        Holder holder;
        View view = convertView;

        if(view == null){
            view = inflater.inflate(R.layout.list_item_user, null);
            holder = new Holder();

            holder.userNameView = view.findViewById(R.id.userName);
            view.setTag(holder);
        }else{

            holder = (Holder) view.getTag();
        }

        String currentUserName = (String) getItem(position);

        holder.userNameView.setText(currentUserName);

        return view;

    }

    class Holder {
        TextView userNameView;
    }
}
