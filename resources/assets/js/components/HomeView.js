import React from 'react'
import {Link} from 'react-router'
import {connect, PromiseState} from "react-refetch"
import PromiseStateContainer from "./core/PromiseStateContainer"
import {Card, CardHeader, CardBlock} from "reactstrap"
import {LineChart} from "./Chart"
import Loading from './core/Loading'
import recordHelper from '../utils/RecordHelper'

class HomeView extends React.Component {

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
                <div className="text-xs-center mb-1 p-1 bg-faded">
                  <h4 className="text-uppercase">Players of the week</h4>
                  <i className="text-muted">Generated in {data.time} seconds{data.cached ? ' (Cached)' : ''}.</i>
                </div>
                <div className="row">
                  <div className="col-md-6">
                    <Card>
                      <CardHeader>
                        Most active player: <Link to={`/players/${mostActivePlayer.id}`}>{mostActivePlayer.name}</Link>
                      </CardHeader>
                      <CardBlock>
                        <LineChart label="Trophies" color={mostActivePlayer.color.value}
                                   labels={recordHelper.filterRecords(mostActivePlayer.records, 'time', false)}
                                   data={recordHelper.filterRecords(mostActivePlayer.records, 'trophies')}/>
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
                                   labels={recordHelper.filterRecords(soManyTrophies.records, 'time', false)}
                                   data={recordHelper.filterRecords(soManyTrophies.records, 'trophies')}/>
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
                                   labels={recordHelper.filterRecords(donor.records, 'time', false)}
                                   data={recordHelper.filterRecords(donor.records, 'donations')}/>
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
                                   labels={recordHelper.filterRecords(donee.records, 'time', false)}
                                   data={recordHelper.filterRecords(donee.records, 'donationsReceived')}/>
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
