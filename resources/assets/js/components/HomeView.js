import React from 'react'
import {Link} from 'react-router'
import {connect, PromiseState} from "react-refetch"
import PromiseStateContainer from "./core/PromiseStateContainer"
import {Card, CardHeader, CardBlock} from "reactstrap"
import {LineChart} from "./Chart"
import Loading from './core/Loading'

class HomeView extends React.Component {
  filterRecords(records, key) {
    return records.map(function(record){return parseInt(record[key]);})
  }

  render() {
    return (
      <PromiseStateContainer
        ps={PromiseState.all([this.props.clanFetch])}
        onPending = {() => <Loading text="Calculating a shit ton of data..."/>}
        onFulfillment={([data]) => {
          const clan = data.clan;
          const mostActivePlayer = data.mostActivePlayer;
          const soManyTrophies = data.soManyTrophies;
          const donor = data.donor;
          const donee = data.donee;
          return (
            <div>
              <div className="text-xs-center">
                <h1 style={{letterSpacing: '0.8rem',fontSize: '4rem'}}>{clan.name}</h1>
                <p>Lv{clan.clanLevel} - {clan.clanPoints} Points - {clan.members} Members</p>
                <p>War: {clan.warWins} wins - {clan.warLosses} losses - {clan.warTies} ties</p>
                <hr/>
                <h4 className="text-xs-center text-uppercase mb-1 p-1 bg-faded">Players of the week</h4>
                <div className="row">
                  <div className="col-md-6">
                    <Card>
                      <CardHeader>
                        Most active player: <Link to={`/players/${mostActivePlayer.id}`}>{mostActivePlayer.name}</Link>
                      </CardHeader>
                      <CardBlock>
                        <LineChart label="Trophies" color={mostActivePlayer.color.value}
                                   labels={this.filterRecords(mostActivePlayer.records, 'created_at')}
                                   data={this.filterRecords(mostActivePlayer.records, 'trophies')}/>
                      </CardBlock>
                    </Card>
                  </div>

                  <div className="col-md-6">
                    <Card>
                      <CardHeader>
                        Largest trophy span: <Link to={`/players/${soManyTrophies.id}`}>{soManyTrophies.name}</Link>
                      </CardHeader>
                      <CardBlock>
                        <LineChart label="Trophies" color={soManyTrophies.color.value}
                                   labels={this.filterRecords(soManyTrophies.records, 'created_at')}
                                   data={this.filterRecords(soManyTrophies.records, 'trophies')}/>
                      </CardBlock>
                    </Card>
                  </div>

                  <div className="col-md-6">
                    <Card>
                      <CardHeader>
                        Donated the most: <Link to={`/players/${donor.id}`}>{donor.name}</Link>
                      </CardHeader>
                      <CardBlock>
                        <LineChart label="Donations" color={donor.color.value}
                                   labels={this.filterRecords(donor.records, 'created_at')}
                                   data={this.filterRecords(donor.records, 'donations')}/>
                      </CardBlock>
                    </Card>
                  </div>

                  <div className="col-md-6">
                    <Card>
                      <CardHeader>
                        Received the most: <Link to={`/players/${donee.id}`}>{donee.name}</Link>
                      </CardHeader>
                      <CardBlock>
                        <LineChart label="Donation Received" color={donee.color.value}
                                   labels={this.filterRecords(donee.records, 'created_at')}
                                   data={this.filterRecords(donee.records, 'donationsReceived')}/>
                      </CardBlock>
                    </Card>
                  </div>
                </div>
              </div>
            </div>
          )
        }}
      />
    )
  }
}

export default connect(props => ({
  clanFetch: '/api/clan',
}))(HomeView)
